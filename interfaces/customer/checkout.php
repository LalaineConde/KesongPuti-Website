<?php
require '../../connection.php';
$page_title = 'Checkout | Kesong Puti';

// Fetch store info if requested
if (isset($_GET['action']) && $_GET['action'] === 'store_info') {
    header('Content-Type: application/json');
    $storeName = isset($_GET['store']) ? trim($_GET['store']) : '';
    $recipientParam = isset($_GET['recipient']) ? trim($_GET['recipient']) : '';
    $response = ['store' => $storeName, 'contacts' => null, 'payment_methods' => []];

    if ($storeName !== '') {
        $recipient = $recipientParam ?: null;

        if (!$recipient) {
            $storeStmt = mysqli_prepare($connection, 'SELECT recipient FROM store WHERE store_name = ? LIMIT 1');
            if ($storeStmt) {
                mysqli_stmt_bind_param($storeStmt, 's', $storeName);
                mysqli_stmt_execute($storeStmt);
                $storeRes = mysqli_stmt_get_result($storeStmt);
                $storeRow = mysqli_fetch_assoc($storeRes);
                mysqli_stmt_close($storeStmt);
                $recipient = $storeRow['recipient'] ?? null;
            }
        }

        $contactStmt = mysqli_prepare($connection, 'SELECT email, phone, address FROM store_contacts WHERE store_name = ? LIMIT 1');
        if ($contactStmt) {
            mysqli_stmt_bind_param($contactStmt, 's', $storeName);
            mysqli_stmt_execute($contactStmt);
            $contactRes = mysqli_stmt_get_result($contactStmt);
            $response['contacts'] = mysqli_fetch_assoc($contactRes) ?: null;
            mysqli_stmt_close($contactStmt);
        }

        if ($recipient) {
            $payStmt = mysqli_prepare($connection, 'SELECT method_name, method_type, account_name, account_number, qr_code FROM payment_methods WHERE status = "published" AND recipient = ?');
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

    <!-- Personal Info -->
    <h3>Personal Information</h3>
    <div class="row checkout-row">
      <div class="field"><label for="fname">First Name</label><input type="text" id="fname" placeholder="Enter your first name" /></div>
      <div class="field"><label for="lname">Last Name</label><input type="text" id="lname" placeholder="Enter your last name" /></div>
    </div>
    <label for="email">Email</label><input type="email" id="email" placeholder="Enter your email" />
    <label for="phone">Phone</label><input type="text" id="phone" placeholder="09XXXXXXXXX" />

    <!-- Delivery / Pickup -->
    <h3>Select Method</h3>
    <div class="options">
      <input type="radio" id="delivery" name="method" value="delivery" checked />
      <label class="option-card" for="delivery">Delivery</label>

      <input type="radio" id="pickup" name="method" value="pickup" />
      <label class="option-card" for="pickup">Pickup</label>
    </div>

    <div id="address-section" class="extra-form" style="display: block">
      <div class="row checkout-row">
        <div class="field"><label for="house">House/Lot/Unit No.</label><input type="text" id="house" placeholder="e.g. Unit 5A" /></div>
        <div class="field"><label for="street">Street Name</label><input type="text" id="street" placeholder="e.g. Mabini St." /></div>
      </div>
      <div class="row checkout-row">
        <div class="field"><label for="barangay">Barangay</label><input type="text" id="barangay" placeholder="e.g. Barangay 123" /></div>
        <div class="field"><label for="city">City</label><input type="text" id="city" placeholder="e.g. Quezon City" /></div>
      </div>
      <div class="row checkout-row">
        <div class="field"><label for="province">Province</label><input type="text" id="province" placeholder="e.g. Laguna" /></div>
        <div class="field"><label for="zip">Zip Code</label><input type="text" id="zip" placeholder="e.g. 1008" /></div>
      </div>
    </div>

    <div id="pickup-section" class="extra-form" style="display:none">
      <label for="pickup-date">Pickup Date</label><input type="date" id="pickup-date" />
      <label for="pickup-time">Pickup Time</label><input type="time" id="pickup-time" />
    </div>

    <!-- Payment Method -->
    <h3>Payment Method</h3>
    <div class="options">
      <input type="radio" id="ewallet" name="payment" value="ewallet" />
      <label class="option-card" for="ewallet">E-Wallet</label>

      <input type="radio" id="bank" name="payment" value="bank" />
      <label class="option-card" for="bank">Bank Transfer</label>

      <input type="radio" id="cash" name="payment" value="cash" checked />
      <label class="option-card" for="cash">Cash</label>
    </div>

    <!-- E-Wallet Form -->
    <div id="ewallet-form" class="extra-form" style="display:none">
      <div id="ewallet-methods"></div>
      <div class="proof-upload">
        <label for="ewallet-proof">Upload Proof of Payment:</label>
        <div class="upload-box" onclick="document.getElementById('ewallet-proof').click();">
          <p class="upload-text">Click or drag & drop your screenshot here</p>
          <img id="ewallet-preview" alt="E-Wallet Proof Preview" />
        </div>
        <input type="file" id="ewallet-proof" accept="image/*" />
      </div>
    </div>

    <!-- Bank Transfer Form -->
    <div id="bank-form" class="extra-form" style="display:none">
      <div id="bank-info"></div>
      <div class="proof-upload">
        <label for="bank-proof">Upload Proof of Payment:</label>
        <div class="upload-box" onclick="document.getElementById('bank-proof').click();">
          <p class="upload-text">Click or drag & drop your screenshot here</p>
          <img id="bank-preview" alt="Bank Proof Preview" />
        </div>
        <input type="file" id="bank-proof" accept="image/*" />
      </div>
    </div>
  </div>

  <!-- Order Summary -->
  <div class="order-summary" id="orderSummary">
    <h2>Order Summary</h2>
    <div id="summaryItems"></div>
    <div class="summary-item" id="deliveryFeeRow" style="display:none">
      <span>Delivery Fee</span><span id="deliveryFee">₱0.00</span>
    </div>
    <div class="total mb-3">
      <span>Total</span><span id="summaryTotal">₱0.00</span>
    </div>
    <button type="button" class="checkout-btn" id="placeOrderBtn">Place Order</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function getCart(){ try{ return JSON.parse(localStorage.getItem('cart'))||[]; } catch(e){ return []; } }
function currency(n){ return `₱${Number(n||0).toFixed(2)}`; }

function renderSummary(){
  const cart = getCart();
  const itemsEl = document.getElementById('summaryItems');
  const totalEl = document.getElementById('summaryTotal');
  itemsEl.innerHTML='';
  if(!cart||cart.length===0){ itemsEl.innerHTML='<p class="text-muted">Your cart is empty.</p>'; totalEl.textContent=currency(0); return null; }
  let sum=0;
  cart.forEach(p=>{
    const itemName=p.name||p.product_name||"Unnamed";
    const itemQty=p.qty||p.quantity||1;
    const itemPrice=p.price||p.unit_price||0;
    const line=itemQty*itemPrice; sum+=line;
    const row=document.createElement('div');
    row.className='summary-item';
    row.innerHTML=`<span>${itemName} x${itemQty}</span><span>${currency(line)}</span>`;
    itemsEl.appendChild(row);
  });
  totalEl.textContent=currency(sum);
  return cart[0]?.store_name||cart[0]?.store||null;
}

async function fetchStoreDetails(storeName){
  if(!storeName) return;
  try{
    const url=new URL(window.location.href);
    url.searchParams.set('action','store_info');
    url.searchParams.set('store',storeName);
    const res=await fetch(url.toString(),{credentials:'same-origin'});
    const data=await res.json();
    
    const ewalletForm=document.getElementById('ewallet-form');
    const bankForm=document.getElementById('bank-form');
    const ewalletMethodsContainer=document.getElementById('ewallet-methods');
    const bankInfoContainer=document.getElementById('bank-info');
    const ewalletRadio=document.getElementById('ewallet');
    const bankRadio=document.getElementById('bank');
    const cashRadio=document.getElementById('cash');

    ewalletForm.style.display='none'; bankForm.style.display='none';
    ewalletMethodsContainer.innerHTML=''; bankInfoContainer.innerHTML='';
    ewalletRadio.disabled=true; bankRadio.disabled=true;
    let hasEwallet=false, hasBank=false;

    if(Array.isArray(data.payment_methods)){
      data.payment_methods.forEach((pm,index)=>{
        const category=(pm.method_name||'').toLowerCase();
        const type=pm.method_type||'';
        if(category==='e-wallet'){
          hasEwallet=true; ewalletRadio.disabled=false;
          const div=document.createElement('div'); div.className='ewallet-method';
          let qrHTML='';
          if(pm.qr_code){ qrHTML=`<br><a href='../../uploads/payment_qr/${pm.qr_code}' target='_blank'><img src='../../uploads/payment_qr/${pm.qr_code}' class='qr' style='max-width:100px'></a>`; }
          div.innerHTML=`<input type='radio' name='ewallet-choice' id='ewallet-${index}' value='${index}' ${index===0?'checked':''}><label for='ewallet-${index}'>
          <strong>Method Type:</strong> ${pm.method_type}
          <br><strong>Account Name:</strong> ${pm.account_name}
          <br><strong>Account Number:</strong> ${pm.account_number}${qrHTML}</label>`;
          ewalletMethodsContainer.appendChild(div);
        }
        if(category==='bank'){
          hasBank=true; bankRadio.disabled=false;
          bankInfoContainer.innerHTML+=`<p><strong>Method Type:</strong> ${type}</p><p><strong>Account Name:</strong> ${pm.account_name}</p><p><strong>Account Number:</strong> ${pm.account_number}</p>`;
        }
      });
    }

    if(hasEwallet){ ewalletRadio.checked=true; ewalletForm.style.display='block'; }
    else if(hasBank){ bankRadio.checked=true; bankForm.style.display='block'; }
    else{ cashRadio.checked=true; }

  }catch(e){ console.error(e); }
}

document.getElementById('delivery').addEventListener('change',()=>{document.getElementById('address-section').style.display='block'; document.getElementById('pickup-section').style.display='none';});
document.getElementById('pickup').addEventListener('change',()=>{document.getElementById('address-section').style.display='none'; document.getElementById('pickup-section').style.display='block';});

document.querySelectorAll('input[name="payment"]').forEach(radio=>{
  radio.addEventListener('change',()=>{
    const ewalletForm=document.getElementById('ewallet-form');
    const bankForm=document.getElementById('bank-form');
    if(document.getElementById('ewallet').checked&&!document.getElementById('ewallet').disabled){ ewalletForm.style.display='block'; bankForm.style.display='none'; }
    else if(document.getElementById('bank').checked&&!document.getElementById('bank').disabled){ bankForm.style.display='block'; ewalletForm.style.display='none'; }
    else{ ewalletForm.style.display='none'; bankForm.style.display='none'; }
  });
});

function previewFile(input, previewId){
  const file=input.files[0]; const preview=document.getElementById(previewId); 
  const uploadBox=preview.closest(".upload-box"); const uploadText=uploadBox.querySelector(".upload-text");
  if(file){ const reader=new FileReader(); reader.onload=(e)=>{ preview.src=e.target.result; preview.style.display="block"; uploadText.style.display="none"; }; reader.readAsDataURL(file); }
}

document.getElementById("ewallet-proof").addEventListener("change",()=>{ previewFile(document.getElementById("ewallet-proof"),"ewallet-preview"); });
document.getElementById("bank-proof").addEventListener("change",()=>{ previewFile(document.getElementById("bank-proof"),"bank-preview"); });

document.addEventListener('DOMContentLoaded',()=>{
  const store=renderSummary();
  if(store) fetchStoreDetails(store);

  document.getElementById('placeOrderBtn').addEventListener('click', async ()=>{
    const cart=getCart();
    if(!cart||cart.length===0){ Swal.fire('Error','Your cart is empty.','error'); return; }

    const customer={
      first_name: document.getElementById('fname').value.trim(),
      last_name: document.getElementById('lname').value.trim(),
      email: document.getElementById('email').value.trim(),
      phone: document.getElementById('phone').value.trim()
    };

    if(!customer.first_name||!customer.last_name||!customer.email||!customer.phone){ Swal.fire('Error','Please fill all customer info.','error'); return; }

    const orderType=document.getElementById('delivery').checked?'delivery':'pickup';
    const deliveryAddress=document.getElementById('delivery').checked?`${document.getElementById('house').value}, ${document.getElementById('street').value}, ${document.getElementById('barangay').value}, ${document.getElementById('city').value}, ${document.getElementById('province').value}, ${document.getElementById('zip').value}`:'';
    const selectedPayment=document.querySelector('input[name="payment"]:checked').value;

    const payload={customer, items:cart, payment_method:selectedPayment, order_type:orderType, delivery_address:deliveryAddress, store_name:store, recipient:cart[0]?.recipient||''};

    try{
      const formData=new FormData();
      formData.append('payload', JSON.stringify(payload));
      if(selectedPayment==='ewallet'){ const proof=document.getElementById('ewallet-proof').files[0]; if(proof) formData.append('ewallet_proof',proof); }
      if(selectedPayment==='bank'){ const proof=document.getElementById('bank-proof').files[0]; if(proof) formData.append('bank_proof',proof); }

      const res=await fetch('place-order.php',{method:'POST',body:formData,credentials:'same-origin'});
      const out=await res.json();
      if(out?.ok){
        localStorage.removeItem('cart');
        Swal.fire({title:'Order Placed Successfully!', text:'Your order has been placed and is being processed.', icon:'success', confirmButtonText:'Continue Shopping', confirmButtonColor:'#28a745', allowOutsideClick:false, allowEscapeKey:false})
          .then(()=>{ window.location='products.php'; });
      }else{ Swal.fire('Error','Failed to place order. '+(out?.error||''),'error'); }
    }catch(e){ console.error(e); Swal.fire('Error','Something went wrong while placing your order.','error'); }
  });
});
</script>
</body>
</html>
