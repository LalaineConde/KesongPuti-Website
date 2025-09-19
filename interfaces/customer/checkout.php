<?php

require '../../connection.php';
$page_title = 'Checkout | Kesong Puti';


if (isset($_GET['action']) && $_GET['action'] === 'store_info') {
	header('Content-Type: application/json');
	$storeName = isset($_GET['store']) ? trim($_GET['store']) : '';
	$recipientParam = isset($_GET['recipient']) ? trim($_GET['recipient']) : '';
	$response = [
		'store' => $storeName,
		'contacts' => null,
		'payment_methods' => []
	];

    if ($storeName !== '') {
        // Determine recipient: prefer explicit recipient param, else derive from store name
        $recipient = null;
        if ($recipientParam !== '') {
            $recipient = $recipientParam;
        } else {
            $storeStmt = mysqli_prepare($connection, 'SELECT recipient FROM store WHERE store_name = ? LIMIT 1');
            if ($storeStmt) {
                mysqli_stmt_bind_param($storeStmt, 's', $storeName);
                mysqli_stmt_execute($storeStmt);
                $storeRes = mysqli_stmt_get_result($storeStmt);
                $storeRow = mysqli_fetch_assoc($storeRes);
                mysqli_stmt_close($storeStmt);
                $recipient = $storeRow ? $storeRow['recipient'] : null;
            }
        }

        // Contacts from store_contacts by store_name
        $contactStmt = mysqli_prepare($connection, 'SELECT email, phone, address FROM store_contacts WHERE store_name = ? LIMIT 1');
        if ($contactStmt) {
            mysqli_stmt_bind_param($contactStmt, 's', $storeName);
            mysqli_stmt_execute($contactStmt);
            $contactRes = mysqli_stmt_get_result($contactStmt);
            $response['contacts'] = mysqli_fetch_assoc($contactRes) ?: null;
            mysqli_stmt_close($contactStmt);
        }

        // Payment methods by recipient if available
        if ($recipient) {
            $payStmt = mysqli_prepare($connection, 'SELECT method_name, account_name, account_number, qr_code FROM payment_methods WHERE status = "published" AND recipient = ?');
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

    <!-- BOOTSTRAP -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr"
      crossorigin="anonymous"
    >

    <!-- ICONS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    >

    <!-- CSS -->
    <link rel="stylesheet" href="../../css/styles.css" >
  </head>

  <body style="background-color: var(--beige)">
    <div class="checkout-wrapper">
      <!-- forms -->
      <div class="checkout-form">
        <h2>Checkout</h2>

        <!-- personal info -->
        <h3>Personal Information</h3>
        <div class="row checkout-row">
          <div class="field">
            <label for="fname">First Name</label>
            <input type="text" id="fname" placeholder="Enter your first name" />
          </div>
          <div class="field">
            <label for="lname">Last Name</label>
            <input type="text" id="lname" placeholder="Enter your last name" />
          </div>
        </div>

        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Enter your email" />

        <label for="phone">Phone</label>
        <input type="text" id="phone" placeholder="09XXXXXXXXX" />

        <!-- delivery or pickup -->
        <h3>Select Method</h3>
        <div class="options">
          <input
            type="radio"
            id="delivery"
            name="method"
            value="delivery"
            checked
          />
          <label class="option-card" for="delivery">Delivery</label>

          <input type="radio" id="pickup" name="method" value="pickup" />
          <label class="option-card" for="pickup">Pickup</label>
        </div>

        <!-- if delivery -->
        <div id="address-section" class="extra-form" style="display: block">
          <div class="row checkout-row">
            <div class="field">
              <label for="house">House/Lot/Unit No.</label>
              <input type="text" id="house" placeholder="e.g. Unit 5A" />
            </div>
            <div class="field">
              <label for="street">Street Name</label>
              <input type="text" id="street" placeholder="e.g. Mabini St." />
            </div>
          </div>

          <div class="row checkout-row">
            <div class="field">
              <label for="barangay">Barangay</label>
              <input
                type="text"
                id="barangay"
                placeholder="e.g. Barangay 123"
              />
            </div>
            <div class="field">
              <label for="city">City</label>
              <input type="text" id="city" placeholder="e.g. Quezon City" />
            </div>
          </div>

          <div class="row checkout-row">
            <div class="field">
              <label for="province">Province</label>
              <input type="text" id="province" placeholder="e.g. Laguna" />
            </div>
            <div class="field">
              <label for="zip">Zip Code</label>
              <input type="text" id="zip" placeholder="e.g. 1008" />
            </div>
          </div>
        </div>

        <!-- if pickup -->
        <div id="pickup-section" class="extra-form">
          <label for="pickup-date">Pickup Date</label>
          <input type="date" id="pickup-date" />

          <label for="pickup-time">Pickup Time</label>
          <input type="time" id="pickup-time" />
        </div>

        <!-- payment options -->
        <h3>Payment Method</h3>
        <div class="mb-2" style="font-size: 0.95rem; color: #555;">
          <strong>Store Contact:</strong>
          <span id="storeContact">Loading contact info…</span>
        </div>
        <div id="storePayments" class="mb-3" style="font-size: 0.95rem; color: #777;"></div>
        <div class="options">
          <input type="radio" id="gcash" name="payment" value="gcash" />
          <label class="option-card" for="gcash">GCash</label>

          <input type="radio" id="bank" name="payment" value="bank" />
          <label class="option-card" for="bank">Bank Transfer</label>

          <input type="radio" id="cash" name="payment" value="cash" checked />
          <label class="option-card" for="cash">Cash</label>
        </div>

        <!-- gcash -->
        <div id="gcash-form" class="extra-form" style="display:none">
          <p><strong>GCash Name:</strong> <span id="gcash-name-val"></span></p>
          <p><strong>GCash Number:</strong> <span id="gcash-number-val"></span></p>
          <img id="gcash-qr" alt="GCash QR Code" class="qr" style="display:none" />

          <div class="proof-upload">
            <label for="gcash-proof">Upload Proof of Payment:</label>
            <div
              class="upload-box"
              onclick="document.getElementById('gcash-proof').click();"
            >
              <p class="upload-text">
                Click or drag & drop your screenshot here
              </p>
              <img id="gcash-preview" alt="GCash Proof Preview" />
            </div>
            <input type="file" id="gcash-proof" accept="image/*" />
          </div>
        </div>

        <!-- bank transfer -->
        <div id="bank-form" class="extra-form" style="display:none">
          <p><strong>Bank:</strong> BDO</p>
          <p><strong>Account Name:</strong> <span id="bank-account-name"></span></p>
          <p><strong>Account Number:</strong> <span id="bank-account-number"></span></p>

          <div class="proof-upload">
            <label for="bank-proof">Upload Proof of Payment:</label>
            <div
              class="upload-box"
              onclick="document.getElementById('bank-proof').click();"
            >
              <p class="upload-text">
                Click or drag & drop your screenshot here
              </p>
              <img id="bank-preview" alt="Bank Proof Preview" />
            </div>
            <input type="file" id="bank-proof" accept="image/*" />
          </div>
        </div>
      </div>

      <!-- order summary -->
      <div class="order-summary" id="orderSummary">
        <h2>Order Summary</h2>
        <div id="summaryItems"></div>
        <div class="summary-item" id="deliveryFeeRow" style="display:none">
          <span>Delivery Fee</span>
          <span id="deliveryFee">₱0.00</span>
        </div>
        <div class="total mb-3">
          <span>Total</span>
          <span id="summaryTotal">₱0.00</span>
        </div>


        <button type="button" class="checkout-btn" id="placeOrderBtn">Place Order</button>
      </div>
    </div>

    <!-- BOOTSTRAP JS -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
      crossorigin="anonymous"
    ></script>

    <!-- SWEETALERT2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CHECKOUT -->
    <script>
      // Load cart from localStorage (single-store enforced by cart logic)
      function getCart() {
        try { return JSON.parse(localStorage.getItem('cart')) || []; } catch(e) { return []; }
      }

      function currency(n) { return `₱${Number(n||0).toFixed(2)}`; }

      // Render order summary using cart
      function renderSummary() {
        const cart = getCart();
        const itemsEl = document.getElementById('summaryItems');
        const totalEl = document.getElementById('summaryTotal');
        itemsEl.innerHTML = '';

        if (cart.length === 0) {
          itemsEl.innerHTML = '<p class="text-muted">Your cart is empty.</p>';
          totalEl.textContent = currency(0);
          return null;
        }

        let sum = 0;
        cart.forEach(p => {
          const line = (p.price || 0) * (p.qty || 0);
          sum += line;
          const row = document.createElement('div');
          row.className = 'summary-item';
          row.innerHTML = `<span>${(p.name||'')} x${(p.qty||0)}</span><span>${currency(line)}</span>`;
          itemsEl.appendChild(row);
        });

        totalEl.textContent = currency(sum);

        // Return the current store name to fetch details
        return (cart[0] && cart[0].store) ? String(cart[0].store) : null;
      }

      async function fetchStoreDetails(storeName) {
        if (!storeName) return;
        try {
          const url = new URL(window.location.href);
          url.searchParams.set('action', 'store_info');
          url.searchParams.set('store', storeName);
          // If any item in cart has a recipient code, forward it to ensure correct matching
          try {
            const cart = getCart();
            const rec = (cart && cart[0] && cart[0].recipient) ? String(cart[0].recipient) : '';
            if (rec) url.searchParams.set('recipient', rec);
          } catch(e) {}
          const res = await fetch(url.toString(), { credentials: 'same-origin' });
          const data = await res.json();

          // Contacts
          const contactEl = document.getElementById('storeContact');
          if (data.contacts) {
            const bits = [];
            if (data.contacts.email) bits.push(`Email: ${data.contacts.email}`);
            if (data.contacts.phone) bits.push(`Phone: ${data.contacts.phone}`);
            if (data.contacts.address) bits.push(`Address: ${data.contacts.address}`);
            contactEl.textContent = bits.join(' • ');
          } else {
            contactEl.textContent = 'No contact info available.';
          }

          // Payment methods -> populate GCash/Bank blocks
          const payEl = document.getElementById('storePayments');
          payEl.innerHTML = '';
          const gcashForm = document.getElementById('gcash-form');
          const bankForm = document.getElementById('bank-form');
          const gcashNameVal = document.getElementById('gcash-name-val');
          const gcashNumberVal = document.getElementById('gcash-number-val');
          const gcashQr = document.getElementById('gcash-qr');
          const bankAccountName = document.getElementById('bank-account-name');
          const bankAccountNumber = document.getElementById('bank-account-number');

          let hasAny = false;
          let hasGcash = false;
          let hasBank = false;
          if (Array.isArray(data.payment_methods)) {
            data.payment_methods.forEach(pm => {
              const method = String(pm.method_name || '').toLowerCase();
              if (method === 'gcash') {
                hasAny = true;
                hasGcash = true;
                if (gcashForm) gcashForm.style.display = 'block';
                if (gcashNameVal) gcashNameVal.textContent = pm.account_name || '';
                if (gcashNumberVal) gcashNumberVal.textContent = pm.account_number || '';
                if (gcashQr) {
                  if (pm.qr_code) {
                    gcashQr.src = `../../uploads/payment_qr/${String(pm.qr_code).replace(/^\/+/, '')}`;
                    gcashQr.style.display = 'block';
                  } else {
                    gcashQr.style.display = 'none';
                  }
                }
              }
              if (method === 'bank' || method === 'bank transfer' || method === 'banktransfer') {
                hasAny = true;
                hasBank = true;
                if (bankForm) bankForm.style.display = 'block';
                if (bankAccountName) bankAccountName.textContent = pm.account_name || '';
                if (bankAccountNumber) bankAccountNumber.textContent = pm.account_number || '';
              }
            });
          }
          if (!hasAny) {
            payEl.innerHTML = '<div class="text-muted">No published payment methods.</div>';
          }

          // Auto-select a default visible method and show its details section
          const gcashRadio = document.getElementById('gcash');
          const bankRadio = document.getElementById('bank');
          const cashRadio = document.getElementById('cash');
          function showForSelection() {
            const gcashHasData = !!(gcashNameVal?.textContent?.trim() || gcashNumberVal?.textContent?.trim() || (gcashQr && gcashQr.style.display === 'block'));
            const bankHasData = !!(bankAccountName?.textContent?.trim() || bankAccountNumber?.textContent?.trim());
            if (gcashRadio?.checked && gcashHasData) {
              gcashForm.style.display = 'block';
              bankForm.style.display = 'none';
              return;
            }
            if (bankRadio?.checked && bankHasData) {
              bankForm.style.display = 'block';
              gcashForm.style.display = 'none';
              return;
            }
            // Fallback: hide both
            if (gcashForm) gcashForm.style.display = 'none';
            if (bankForm) bankForm.style.display = 'none';
          }
          if (hasGcash) {
            if (gcashRadio) gcashRadio.checked = true;
          } else if (hasBank) {
            if (bankRadio) bankRadio.checked = true;
          } else if (cashRadio) {
            cashRadio.checked = true;
          }
          showForSelection();
        } catch (e) {
          console.error(e);
        }
      }
      // Toggle delivery vs pickup
      const delivery = document.getElementById("delivery");
      const pickup = document.getElementById("pickup");
      const addressSection = document.getElementById("address-section");
      const pickupSection = document.getElementById("pickup-section");

      delivery.addEventListener("change", () => {
        addressSection.style.display = "block";
        pickupSection.style.display = "none";
      });
      pickup.addEventListener("change", () => {
        addressSection.style.display = "none";
        pickupSection.style.display = "block";
      });

      // Toggle payment forms
      const paymentRadios = document.querySelectorAll('input[name="payment"]');
      const gcashForm = document.getElementById("gcash-form");
      const bankForm = document.getElementById("bank-form");

      paymentRadios.forEach((radio) => {
        radio.addEventListener("change", () => {
          const gcashForm = document.getElementById('gcash-form');
          const bankForm = document.getElementById('bank-form');
          const gcashHasData = !!(document.getElementById('gcash-name-val')?.textContent?.trim() || document.getElementById('gcash-number-val')?.textContent?.trim() || (document.getElementById('gcash-qr') && document.getElementById('gcash-qr').style.display === 'block'));
          const bankHasData = !!(document.getElementById('bank-account-name')?.textContent?.trim() || document.getElementById('bank-account-number')?.textContent?.trim());
          if (document.getElementById("gcash").checked && gcashHasData) {
            gcashForm.style.display = 'block';
            bankForm.style.display = 'none';
            return;
          }
          if (document.getElementById("bank").checked && bankHasData) {
            bankForm.style.display = 'block';
            gcashForm.style.display = 'none';
            return;
          }
          gcashForm.style.display = 'none';
          bankForm.style.display = 'none';
        });
      });

      // File preview function
      function previewFile(input, previewId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);
        const uploadBox = preview.closest(".upload-box");
        const uploadText = uploadBox.querySelector(".upload-text");

        if (file) {
          const reader = new FileReader();
          reader.onload = (e) => {
            preview.src = e.target.result;
            preview.style.display = "block";
            uploadText.style.display = "none"; // hide text when image is shown
          };
          reader.readAsDataURL(file);
        }
      }

      document
        .getElementById("gcash-proof")
        .addEventListener("change", function () {
          previewFile(this, "gcash-preview");
        });

      document
        .getElementById("bank-proof")
        .addEventListener("change", function () {
          previewFile(this, "bank-preview");
        });

      // Initialize summary and store details on load
      document.addEventListener('DOMContentLoaded', () => {
        const store = renderSummary();
        if (store) fetchStoreDetails(store);
      });

      // Place Order action: submit to backend
      const placeOrderBtn = document.getElementById('placeOrderBtn');
      if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', async () => {
          const cart = getCart();
          if (!cart || cart.length === 0) { alert('Your cart is empty.'); return; }

          const customer = {
            first_name: document.getElementById('fname').value.trim(),
            last_name: document.getElementById('lname').value.trim(),
            email: document.getElementById('email').value.trim(),
            phone: document.getElementById('phone').value.trim(),
          };

          const method = document.querySelector('input[name="method"]:checked')?.value || 'delivery';
          const orderType = method;
          let address = null;
          if (orderType === 'delivery') {
            const house = document.getElementById('house').value.trim();
            const street = document.getElementById('street').value.trim();
            const barangay = document.getElementById('barangay').value.trim();
            const city = document.getElementById('city').value.trim();
            const province = document.getElementById('province').value.trim();
            const zip = document.getElementById('zip').value.trim();
            address = [house, street, barangay, city, province, zip].filter(Boolean).join(', ');
          }

          const selectedPayment = document.querySelector('input[name="payment"]:checked')?.value || 'cash';
          const payload = {
            customer,
            items: cart.map(i => ({ id: i.id, qty: i.qty, price: i.price })),
            payment_method: selectedPayment,
            payment_method_id: null,
            order_type: orderType,
            delivery_address: address,
            store_name: cart[0]?.store || null,
            recipient: cart[0]?.recipient || null
          };

          try {
            console.log('Sending payload:', payload);
            const res = await fetch('place-order.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify(payload),
              credentials: 'same-origin'
            });
            const out = await res.json();
            console.log('Response:', out);
            if (out && out.ok) {
              // clear cart first
              localStorage.removeItem('cart');
              
              // Show success alert
              Swal.fire({
                title: 'Order Placed Successfully!',
                text: 'Your order has been placed and is being processed. You will receive a confirmation email shortly.',
                icon: 'success',
                confirmButtonText: 'Continue Shopping',
                confirmButtonColor: '#28a745',
                allowOutsideClick: false,
                allowEscapeKey: false
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href = 'products.php';
                }
              });
            } else {
              Swal.fire({
                title: 'Order Failed',
                text: 'Failed to place order: ' + (out.error || 'Unknown error') + (out.mysql_error ? ' - ' + out.mysql_error : ''),
                icon: 'error',
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#dc3545'
              });
            }
          } catch (e) {
            console.error(e);
            Swal.fire({
              title: 'Connection Error',
              text: 'Unexpected error while placing order: ' + e.message,
              icon: 'error',
              confirmButtonText: 'Try Again',
              confirmButtonColor: '#dc3545'
            });
          }
        });
      }
    </script>
  </body>
</html>
