// age-calculator.js

jQuery(document).ready(function($) {
    // Initialize date input with today's date as max
    const today = new Date().toISOString().split('T')[0];
    $('#birthDate').attr('max', today);

    function calculateAge(birthDate, cutoffDate) {
        const birth = new Date(birthDate);
        const cutoff = new Date(cutoffDate);
        
        let years = cutoff.getFullYear() - birth.getFullYear();
        let months = cutoff.getMonth() - birth.getMonth();
        let days = cutoff.getDate() - birth.getDate();

        if (days < 0) {
            months--;
            const lastMonth = new Date(cutoff.getFullYear(), cutoff.getMonth(), 0);
            days += lastMonth.getDate();
        }
        if (months < 0) {
            years--;
            months += 12;
        }

        return { years, months, days };
    }

    function getYearGroup(years) {
        const yearGroups = {
            3: 'FS1',
            4: 'FS2',
            5: 'Year 1',
            6: 'Year 2',
            7: 'Year 3',
            8: 'Year 4',
            9: 'Year 5',
            10: 'Year 6',
            11: 'Year 7',
            12: 'Year 8',
            13: 'Year 9',
            14: 'Year 10',
            15: 'Year 11',
            16: 'Year 12',
            17: 'Year 13'
        };
        return yearGroups[years] || null;
    }

    function getEnrollmentStatus(age) {
        const yearGroup = getYearGroup(age.years);
        
        if (age.years < 3) {
            return 'Too young for enrollment as per UK National guidelines.';
        } else if (age.years >= 18) {
            return 'Not eligible to any standard as per UK National.';
        } else {
            return `Eligible for enrollment in ${yearGroup} as per UK National guidelines.`;
        }
    }

    function formatAgeDisplay(age) {
        return `${age.years} Years, ${age.months} Months and ${age.days} Days old.`;
    }

    $('#calculateAge').on('click', function() {
        const birthDate = $('#birthDate').val();
        const academicYear = $('#academicYear').val();

        // Validation
        if (!birthDate) {
            alert('Please select the date of birth');
            return;
        }

        // Calculate age as of August 31st of selected academic year
        const cutoffDate = `${academicYear}-08-31`;
        const age = calculateAge(birthDate, cutoffDate);
        
        // Update status message
        const status = getEnrollmentStatus(age);
        $('.status-message').text(status);
        
        // Update age details
        $('.academic-year-title').text(
            `Age On 31 - Aug - ${academicYear} (Academic Year ${academicYear}-${parseInt(academicYear) + 1})`
        );
        $('.age-result').text(formatAgeDisplay(age));
        
        // Show results with animation
        $('#calculationResult')
            .hide()
            .removeClass('show')
            .addClass('show')
            .slideDown(300);
    });

    // Reset form
    function resetCalculator() {
        $('#calculationResult').slideUp(300);
        $('#birthDate').val('');
    }

    // Handle academic year change
    $('#academicYear').on('change', function() {
        if ($('#birthDate').val()) {
            $('#calculateAge').trigger('click');
        }
    });
});