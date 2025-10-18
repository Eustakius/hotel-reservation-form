// Cross-browser DOM ready function
(function() {
    'use strict';
    
    // Wait for DOM to load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initValidation);
    } else {
        initValidation();
    }
    
    function initValidation() {
        var form = document.getElementById('reservationForm');
        if (!form) return;
        
        // Add event listener for form submission
        if (form.addEventListener) {
            form.addEventListener('submit', validateForm, false);
        } else if (form.attachEvent) {
            form.attachEvent('onsubmit', validateForm);
        }
    }
    
    function validateForm(e) {
        var errors = [];
        
        // Get form elements
        var name = document.getElementById('name');
        var genderMale = document.getElementById('gender-male');
        var genderFemale = document.getElementById('gender-female');
        var email = document.getElementById('email');
        var telp = document.getElementById('telp');
        var reservator = document.getElementById('reservator');
        var bookingDate = document.getElementById('booking_date');
        var roomType = document.getElementById('roomtype');
        var bedSingle = document.getElementById('bed-single');
        var bedDouble = document.getElementById('bed-double');
        var withBreakfast = document.getElementById('with-breakfast');
        var withoutBreakfast = document.getElementById('without-breakfast');
        var checkinDate = document.getElementById('checkin');
        var checkoutDate = document.getElementById('checkout');
        var payment = document.getElementById('payment');
        
        // Validate name
        if (!name.value || name.value.trim() === '') {
            errors.push('Name is required');
        } else if (name.value.trim().length < 3) {
            errors.push('Name must be at least 3 characters');
        } else if (!/^[a-zA-Z\s]+$/.test(name.value)) {
            errors.push('Name must contain only letters and spaces');
        }
        
        // Validate gender
        if (!genderMale.checked && !genderFemale.checked) {
            errors.push('Please select gender');
        }
        
        // Validate email
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.value || email.value.trim() === '') {
            errors.push('Email is required');
        } else if (!emailPattern.test(email.value)) {
            errors.push('Invalid email format (example: user@domain.com)');
        }
        
        // Validate phone
        var phonePattern = /^[0-9+\-\s()]+$/;
        if (!telp.value || telp.value.trim() === '') {
            errors.push('Phone number is required');
        } else if (!phonePattern.test(telp.value)) {
            errors.push('Invalid phone number format');
        } else if (telp.value.trim().length < 8) {
            errors.push('Phone number is too short (minimum 8 digits)');
        }
        
        // Validate reservator name
        if (!reservator.value || reservator.value.trim() === '') {
            errors.push('Reservator name is required');
        } else if (reservator.value.trim().length < 3) {
            errors.push('Reservator name must be at least 3 characters');
        }
        
        // Validate booking date
        var datePattern = /^\d{4}-\d{2}-\d{2}$/;
        if (!bookingDate.value || bookingDate.value.trim() === '') {
            errors.push('Booking date is required (format: YYYY-MM-DD)');
        } else if (!datePattern.test(bookingDate.value)) {
            errors.push('Booking date must be in format YYYY-MM-DD');
        } else if (!isValidDate(bookingDate.value)) {
            errors.push('Booking date is not a valid date');
        }
        
        // Validate room type
        if (!roomType.value || roomType.value === '') {
            errors.push('Please select room type');
        }
        
        // Validate bed type
        if (!bedSingle.checked && !bedDouble.checked) {
            errors.push('Please select bed type');
        }
        
        // Validate breakfast
        if (!withBreakfast.checked && !withoutBreakfast.checked) {
            errors.push('Please select breakfast option');
        }
        
        // Validate check-in date
        if (!checkinDate.value || checkinDate.value.trim() === '') {
            errors.push('Check-in date is required (format: YYYY-MM-DD)');
        } else if (!datePattern.test(checkinDate.value)) {
            errors.push('Check-in date must be in format YYYY-MM-DD');
        } else if (!isValidDate(checkinDate.value)) {
            errors.push('Check-in date is not a valid date');
        }
        
        // Validate check-out date
        if (!checkoutDate.value || checkoutDate.value.trim() === '') {
            errors.push('Check-out date is required (format: YYYY-MM-DD)');
        } else if (!datePattern.test(checkoutDate.value)) {
            errors.push('Check-out date must be in format YYYY-MM-DD');
        } else if (!isValidDate(checkoutDate.value)) {
            errors.push('Check-out date is not a valid date');
        }
        
        // Validate date logic
        if (datePattern.test(checkinDate.value) && datePattern.test(checkoutDate.value) && 
            isValidDate(checkinDate.value) && isValidDate(checkoutDate.value)) {
            
            var checkin = new Date(checkinDate.value);
            var checkout = new Date(checkoutDate.value);
            var today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (checkin < today) {
                errors.push('Check-in date cannot be in the past');
            }
            
            if (checkout <= checkin) {
                errors.push('Check-out date must be after check-in date');
            }
            
            var daysDiff = Math.floor((checkout - checkin) / (1000 * 60 * 60 * 24));
            if (daysDiff > 30) {
                errors.push('Maximum stay duration is 30 days');
            }
        }
        
        // Validate payment method
        if (!payment.value || payment.value === '') {
            errors.push('Please select payment method');
        }
        
        // Show errors if any
        if (errors.length > 0) {
            alert('Please correct the following errors:\n\n' + errors.join('\n'));
            
            // Prevent form submission
            if (e.preventDefault) {
                e.preventDefault();
            } else {
                e.returnValue = false;
            }
            return false;
        }
        
        return true;
    }
    
    // Helper function to validate date
    function isValidDate(dateString) {
        var parts = dateString.split('-');
        if (parts.length !== 3) return false;
        
        var year = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        var day = parseInt(parts[2], 10);
        
        if (isNaN(year) || isNaN(month) || isNaN(day)) return false;
        if (month < 1 || month > 12) return false;
        if (day < 1 || day > 31) return false;
        
        // Check month-specific day limits
        var daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        
        // Check for leap year
        if ((year % 4 === 0 && year % 100 !== 0) || year % 400 === 0) {
            daysInMonth[1] = 29;
        }
        
        if (day > daysInMonth[month - 1]) return false;
        
        return true;
    }
})();
