// Amount button functionality
document.addEventListener('DOMContentLoaded', function() {
    const amountBtns = document.querySelectorAll('.amount-btn');
    const donationAmount = document.getElementById('donationAmount');
    const customBtn = document.getElementById('customBtn');
    
    if (amountBtns && donationAmount) {
        amountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                amountBtns.forEach(b => b.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Set amount if not custom button
                if (this.dataset.amount) {
                    donationAmount.value = this.dataset.amount;
                } else if (this.id === 'customBtn') {
                    donationAmount.focus();
                }
            });
        });
        
        // Handle custom amount input
        donationAmount.addEventListener('input', function() {
            const currentValue = this.value;
            let hasMatchingBtn = false;
            
            amountBtns.forEach(btn => {
                if (btn.dataset.amount === currentValue) {
                    btn.classList.add('active');
                    hasMatchingBtn = true;
                } else {
                    btn.classList.remove('active');
                }
            });
            
            // If no matching button, activate custom button
            if (!hasMatchingBtn && customBtn) {
                customBtn.classList.add('active');
            }
        });
    }
    
    // Form validation
    const donationForm = document.getElementById('donationForm');
    if (donationForm) {
        donationForm.addEventListener('submit', function(e) {
            const amount = parseFloat(donationAmount.value);
            const itemId = document.getElementById('item_id').value;
            
            if (amount < 1) {
                e.preventDefault();
                alert('Please enter a valid donation amount (minimum $1)');
                return false;
            }
            
            if (!itemId) {
                e.preventDefault();
                alert('Please select an item to donate');
                return false;
            }
        });
    }
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Mobile menu toggle (if needed)
function toggleMobileMenu() {
    const nav = document.querySelector('nav ul');
    if (nav) {
        nav.classList.toggle('mobile-active');
    }
}

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert-success, .alert-error');
    alerts.forEach(alert => {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
