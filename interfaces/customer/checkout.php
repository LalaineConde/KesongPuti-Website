<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../connection.php';
$page_title = 'Checkout | Kesong Puti';

// Fetch store info if requested
if (isset($_GET['action']) && $_GET['action'] === 'store_info') {
    header('Content-Type: application/json');
    $storeName = isset($_GET['store']) ? trim($_GET['store']) : '';
    $response = ['store' => $storeName, 'contacts' => null, 'payment_methods' => [], 'owner_id' => null];

    if ($storeName !== '') {
        // Get the recipient + owner_id for this store
        $storeStmt = mysqli_prepare($connection, 'SELECT recipient, owner_id FROM store WHERE store_name = ? LIMIT 1');
        $recipient = null;
        $ownerId = null;
        if ($storeStmt) {
            mysqli_stmt_bind_param($storeStmt, 's', $storeName);
            mysqli_stmt_execute($storeStmt);
            $storeRes = mysqli_stmt_get_result($storeStmt);
            $storeRow = mysqli_fetch_assoc($storeRes);
            mysqli_stmt_close($storeStmt);

            $recipient = $storeRow['recipient'] ?? null;
            $ownerId = $storeRow['owner_id'] ?? null;
            $response['owner_id'] = $ownerId;
        }

        // Get store contacts
        $contactStmt = mysqli_prepare($connection, 'SELECT email, phone, address FROM store_contacts WHERE store_name = ? LIMIT 1');
        if ($contactStmt) {
            mysqli_stmt_bind_param($contactStmt, 's', $storeName);
            mysqli_stmt_execute($contactStmt);
            $contactRes = mysqli_stmt_get_result($contactStmt);
            $response['contacts'] = mysqli_fetch_assoc($contactRes) ?: null;
            mysqli_stmt_close($contactStmt);
        }

        // Fetch payment methods for the store's recipient
        if ($recipient) {
            $payStmt = mysqli_prepare($connection,
                'SELECT recipient, method_name, method_type, account_name, account_number, qr_code
                 FROM payment_methods
                 WHERE status = "published" AND recipient = ?'
            );
            if ($payStmt) {
                mysqli_stmt_bind_param($payStmt, 's', $recipient);
                mysqli_stmt_execute($payStmt);
                $payRes = mysqli_stmt_get_result($payStmt);
                while ($row = mysqli_fetch_assoc($payRes)) {
                    $response['payment_methods'][] = $row;
                }
                mysqli_stmt_close($payStmt);
            }
        }
    }

    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Kesong Puti - Check Out</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../../css/styles.css" >
</head>
<body style="background-color: var(--beige)">
<div class="checkout-wrapper">

  <!-- Checkout Form -->
  <div class="checkout-form">
    <h2>Checkout</h2>
    <form id="checkoutForm" enctype="multipart/form-data">
      <!-- Personal Info -->
      <h3>Personal Information</h3>
      <div class="row checkout-row">
        <div class="field"><label for="fname">First Name</label><input type="text" id="fname" name="fname" placeholder="Enter your first name" required /></div>
        <div class="field"><label for="lname">Last Name</label><input type="text" id="lname" name="lname" placeholder="Enter your last name" required /></div>
      </div>
      <label for="email">Email</label><input type="email" id="email" name="email" placeholder="Enter your email" required />
      <label for="phone">Phone</label><input type="text" id="phone" name="phone" placeholder="09XXXXXXXXX" required />

      <!-- Delivery / Pickup -->
      <h3>Select Method</h3>
      <div class="options">
        <input type="radio" id="delivery" name="delivery_type" value="delivery" checked />
        <label class="option-card" for="delivery">Delivery</label>

        <input type="radio" id="pickup" name="delivery_type" value="pickup" />
        <label class="option-card" for="pickup">Pickup</label>
      </div>

      <div id="address-section" class="extra-form" style="display: block">
        <div class="row checkout-row">
          <div class="field"><label for="house">House/Lot/Unit No.</label><input type="text" id="house" name="house" placeholder="e.g. Unit 5A" /></div>
          <div class="field"><label for="street">Street Name</label><input type="text" id="street" name="street" placeholder="e.g. Mabini St." /></div>
        </div>
        <div class="row checkout-row">
          <div class="field"><label for="barangay">Barangay</label><input type="text" id="barangay" name="barangay" placeholder="e.g. Barangay 123" /></div>
          <div class="field"><label for="city">City</label><input type="text" id="city" name="city" placeholder="e.g. Quezon City" /></div>
        </div>
        <div class="row checkout-row">
          <div class="field"><label for="province">Province</label><input type="text" id="province" name="province" placeholder="e.g. Laguna" /></div>
          <div class="field"><label for="zip">Zip Code</label><input type="text" id="zip" name="zip" placeholder="e.g. 1008" /></div>
        </div>
      </div>

      <div id="pickup-section" class="extra-form" style="display:none">
        <label for="pickup-date">Pickup Date</label><input type="date" id="pickup-date" name="pickup-date" />
        <label for="pickup-time">Pickup Time</label><input type="time" id="pickup-time" name="pickup-time" />
      </div>

      <!-- Payment Method -->
      <h3>Payment Method</h3>
      <div class="options">
        <input type="radio" id="ewallet" name="payment" value="ewallet" disabled />
        <label class="option-card" for="ewallet">E-Wallet</label>
        <input type="radio" id="bank" name="payment" value="bank" disabled />
        <label class="option-card" for="bank">Bank Transfer</label>
        <input type="radio" id="cash" name="payment" value="cash" checked />
        <label class="option-card" for="cash">Cash</label>
      </div>
      <div id="ewallet-form" class="extra-form" style="display:none">
        <div id="ewallet-methods"></div>
        <div class="proof-upload">
          <label for="ewallet-proof">Upload Proof of Payment:</label>
          <div class="upload-box" onclick="document.getElementById('ewallet-proof').click();">
            <p class="upload-text">Click or drag & drop your screenshot here</p>
            <img id="ewallet-preview" alt="E-Wallet Proof Preview" style="display:none; max-width:200px;"/>
          </div>
          <!-- unified name: payment_proof -->
          <input type="file" id="ewallet-proof" name="payment_proof" accept="image/*" />
          <button type="button" id="ewallet-remove" class="btn btn-sm btn-danger" style="display:none; margin-top:5px;">Delete Proof</button>
        </div>
      </div>
      <div id="bank-form" class="extra-form" style="display:none">
        <div id="bank-info"></div>
        <div class="proof-upload">
          <label for="bank-proof">Upload Proof of Payment:</label>
          <div class="upload-box" onclick="document.getElementById('bank-proof').click();">
            <p class="upload-text">Click or drag & drop your screenshot here</p>
            <img id="bank-preview" alt="Bank Proof Preview" style="display:none; max-width:200px;"/>
          </div>
          <!-- unified name: payment_proof -->
          <input type="file" id="bank-proof" name="payment_proof" accept="image/*" />
          <button type="button" id="bank-remove" class="btn btn-sm btn-danger" style="display:none; margin-top:5px;">Delete Proof</button>
        </div>
      </div>
      <!-- Hidden fields -->
      <input type="hidden" id="recipient" name="recipient">
      <input type="hidden" id="owner_id" name="owner_id">
    </form>
  </div>

  <!-- Order Summary -->
  <div class="order-summary" id="orderSummary">
    <h2>Order Summary</h2>
    <div id="summaryItems"></div>
    <div class="summary-item" id="deliveryFeeRow" style="display:none">
      <span>Delivery Fee</span><span id="deliveryFee">₱0.00</span>
    </div>
    <div class="total mb-3">
      <span>Total: </span><span id="summaryTotal">₱0.00</span>
    </div>
    <button type="button" class="checkout-btn" id="placeOrderBtn">Place Order</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
  function currency(n){ return '₱'+Number(n||0).toFixed(2); }

  function renderSummary(){
    const cart = JSON.parse(localStorage.getItem('cart')||'[]');
    const itemsEl = document.getElementById('summaryItems');
    const totalEl = document.getElementById('summaryTotal');
    itemsEl.innerHTML='';
    if(cart.length===0){ 
      totalEl.textContent=currency(0); 
      itemsEl.innerHTML='<p class="text-muted">Your cart is empty.</p>'; 
      return null;
    }
    let sum=0;
    cart.forEach((p,index)=>{
      const name=p.name||p.product_name||"Unnamed";
      const qty=p.qty||p.quantity||1;
      const price=p.price||0;
      sum+=qty*price;
      const row=document.createElement('div'); row.className='summary-item';
      row.innerHTML=`<span>${name} x${qty}</span><span>${currency(qty*price)}</span>`;
      itemsEl.appendChild(row);
    });
    totalEl.textContent=currency(sum);
    // storeName can be from the first item
    return cart.length > 0 ? cart[0].store_name || cart[0].store || '' : null;
  }

  const storeName = renderSummary();
  if(!storeName) return;
  const res = await fetch(`${window.location.pathname}?action=store_info&store=${encodeURIComponent(storeName)}`);
  const data = await res.json();
  console.log("Fetched payment methods:", data.payment_methods);

  if(data.payment_methods.length>0) document.getElementById('recipient').value=data.payment_methods[0].recipient;
  if(data.owner_id) document.getElementById('owner_id').value=data.owner_id;
  console.log("Owner ID set:", document.getElementById('owner_id').value);

  // Set up payment method display
  const ewalletRadio=document.getElementById('ewallet');
  const bankRadio=document.getElementById('bank');
  const cashRadio=document.getElementById('cash');
  const ewalletContainer=document.getElementById('ewallet-methods');
  const bankContainer=document.getElementById('bank-info');
  ewalletContainer.innerHTML=''; 
  bankContainer.innerHTML=''; 
  ewalletRadio.disabled=true;
  bankRadio.disabled=true;
  cashRadio.disabled=false;

  let hasEwallet=false, hasBank=false;
  data.payment_methods.forEach((pm, i) => {
    let qrHTML = pm.qr_code ? `<img src='../../uploads/payment_qr/${pm.qr_code}' style='max-width:100px'>` : '';
    if (pm.method_name && pm.method_name.toLowerCase() === 'e-wallet') {
      hasEwallet = true;
      ewalletRadio.disabled = false;
      const div = document.createElement('div'); 
      div.className = 'ewallet-method';
      div.innerHTML = `
        <input type='radio' name='ewallet-choice' id='ewallet-${i}' value='${i}' ${i===0?'checked':''}>
        <label for='ewallet-${i}'>
          <p><strong>E-Wallet Type:</strong> ${pm.method_type || ''}</p>
          <p><strong>Account Name:</strong> ${pm.account_name}</p>
          <p><strong>Account Number:</strong> ${pm.account_number}</p>
          ${qrHTML}
        </label>`;
      ewalletContainer.appendChild(div);
    }
    if (pm.method_name && pm.method_name.toLowerCase() === 'bank') {
      hasBank = true;
      bankRadio.disabled = false;
      bankContainer.innerHTML += `
        <div class="bank-method">
          <p><strong>Bank Name:</strong> ${pm.method_type || ''}</p>
          <p><strong>Account Name:</strong> ${pm.account_name}</p>
          <p><strong>Account Number:</strong> ${pm.account_number}</p>
          ${qrHTML}
        </div>`;
    }
  });

  // Default selection
  if(hasEwallet){ 
    ewalletRadio.checked=true; 
    document.getElementById('ewallet-form').style.display='block'; 
  }
  else if(hasBank){ 
    bankRadio.checked=true; 
    document.getElementById('bank-form').style.display='block'; 
  }
  else{ 
    cashRadio.checked=true; 
  }

  // Address / Pickup toggle
      document.getElementById('delivery').addEventListener('change', ()=>{ 
      document.getElementById('address-section').style.display='block'; 
      document.getElementById('pickup-section').style.display='none'; 

      // Require only address fields
      ['house','street','barangay','city','province','zip'].forEach(id=>{
        document.getElementById(id).required = true;
      });
      ['pickup-date','pickup-time'].forEach(id=>{
        document.getElementById(id).required = false;
      });
    });

    document.getElementById('pickup').addEventListener('change', ()=>{ 
      document.getElementById('address-section').style.display='none'; 
      document.getElementById('pickup-section').style.display='block'; 

      // Require only pickup fields
      ['pickup-date','pickup-time'].forEach(id=>{
        document.getElementById(id).required = true;
      });
      ['house','street','barangay','city','province','zip'].forEach(id=>{
        document.getElementById(id).required = false;
      });
    });

  // Payment toggle
  document.querySelectorAll('input[name="payment"]').forEach(r=>{
    r.addEventListener('change', ()=>{
      document.getElementById('ewallet-form').style.display=(document.getElementById('ewallet').checked && !document.getElementById('ewallet').disabled)?'block':'none';
      document.getElementById('bank-form').style.display=(document.getElementById('bank').checked && !document.getElementById('bank').disabled)?'block':'none';
    });
  });

  function setupProof(inputId,previewId,btnId){
    const input=document.getElementById(inputId), preview=document.getElementById(previewId), btn=document.getElementById(btnId);
    input.addEventListener('change',()=>{ 
      if(input.files[0]){ 
        const reader=new FileReader(); 
        reader.onload=e=>{ 
          preview.src=e.target.result; 
          preview.style.display='block'; 
          btn.style.display='inline-block'; 
        }; 
        reader.readAsDataURL(input.files[0]); 
      }
    });
    btn.addEventListener('click',()=>{ 
      input.value=''; 
      preview.src=''; 
      preview.style.display='none'; 
      btn.style.display='none'; 
    });
  }
  setupProof('ewallet-proof','ewallet-preview','ewallet-remove');
  setupProof('bank-proof','bank-preview','bank-remove');

  document.getElementById('placeOrderBtn').addEventListener('click', async () => {
    // Collect customer info for guest checkout
    const fname = document.getElementById('fname').value.trim();
    const lname = document.getElementById('lname').value.trim();
    const fullname = fname + ' ' + lname;
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();

    const recipient = document.getElementById('recipient').value;
    const ownerId = document.getElementById('owner_id').value;

    const deliveryType = document.querySelector('input[name="delivery_type"]:checked').value;
    let paymentMethod = '';
    if (document.getElementById('ewallet').checked) paymentMethod = 'ewallet';
    else if (document.getElementById('bank').checked) paymentMethod = 'bank';
    else paymentMethod = 'cash';

    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    if (cart.length === 0) {
      Swal.fire('Error', 'Your cart is empty.', 'error');
      return;
    }

    // Ensure every cart item has product_id
    for (const item of cart) {
      if (!item.product_id) {
        Swal.fire('Error', 'Cart item is missing product_id. Please contact support.', 'error');
        return;
      }
    }

    let total = 0;
    cart.forEach(item => {
      total += (item.qty || 1) * (item.price || 0);
    });

    let address = '';
    if (deliveryType === 'delivery') {
      address = [
        document.getElementById('house').value,
        document.getElementById('street').value,
        document.getElementById('barangay').value,
        document.getElementById('city').value,
        document.getElementById('province').value,
        document.getElementById('zip').value
      ].filter(Boolean).join(', ');
    }

    // Append dynamic data
    const formData = new FormData(document.getElementById('checkoutForm'));
    formData.append('fullname', fullname);
    formData.append('email', email);
    formData.append('phone', phone);
    formData.append('recipient', recipient);
    formData.append('owner_id', ownerId);
    formData.append('delivery_type', deliveryType);
    formData.append('address', address);
    formData.append('payment_method', paymentMethod);
    formData.append('cart', JSON.stringify(cart));
    formData.append('total', total);
    
    try {
      const response = await fetch('../../interfaces/customer/place-order.php', {
        method: 'POST',
        body: formData
      });
      const text = await response.text();
      console.log("Raw response from server:", text);

      let result;
      try {
        result = JSON.parse(text);
      } catch (e) {
        Swal.fire({ icon: 'error', title: 'Server Error', text: 'Server did not return valid JSON.\n' + text });
        return;
      }

      if (result.status === 'success') {
        Swal.fire({ icon: 'success', title: 'Order placed!', text: 'Your order has been placed successfully.' })
        .then(() => {
          localStorage.removeItem('cart');
          window.location.href = 'products.php';
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: `Failed to place order: ${result.message}` });
      }
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong while placing your order.' });
    }
  });
});
</script>
</body>
</html>
