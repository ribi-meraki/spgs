<?php
if (!function_exists('spgs_age_calculator_shortcode')) {
    function spgs_age_calculator_shortcode() {
        if (!wp_script_is('jquery', 'done')) {
            wp_enqueue_script('jquery');
        }
        
        ob_start();
        ?>
        <!-- Enrolment Calculator Section -->
        <section class="spgs-enrolment-calculator" aria-label="Enrolment Age Calculator">
            <!-- Main Trigger Button -->
            <button id="openCalculator" class="calc-btn">
                <img src="https://meraki-education.com/spgs/wp-content/uploads/2026/02/accounting.webp" 
                     alt="Calculator Icon" 
                     width="24" 
                     height="24" 
                     style="margin-right: 8px;">
                Enrolment Age Calculator
            </button>

            <!-- Modal Popup -->
            <div id="calculatorModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close-btn">&times;</span>
                    
                    <div class="spgs-calculator">
                        <div class="form-wrap">
                            <div class="form-group">
                                <label>Academic Year*</label>
                                <select id="spgs_academic_year" class="form-control">
                                    <?php
                                    $current_year = date('Y');
                                    for ($i = 0; $i < 4; $i++) {
                                        $year = $current_year + $i;
                                        printf(
                                            '<option value="%1$d"%2$s>%1$d-%3$d</option>',
                                            $year,
                                            $i === 0 ? ' selected' : '',
                                            $year + 1
                                        );
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Child's Date of Birth*</label>
                                <input type="date" id="spgs_birth_date" class="form-control">
                            </div>

                            <button id="spgs_calculate_age" class="calc-btn">Calculate Age</button>
                        </div>

                        <div id="spgs_calculation_result" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </section>

        <style>
        .spgs-enrolment-calculator {
            margin: 20px 0;
            font-family: inherit;
        }

        /* Button Style */
        .calc-btn {
            background: #ae3331;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease;
        }

        .calc-btn:hover {
            background: #ae3331;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 99999;
        }

        .modal-content {
            position: relative;
            background-color: #fff;
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .close-btn {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        .close-btn:hover {
            color: #000;
        }

        /* Form Styles */
        .form-wrap {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 15px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #ae3331;
            box-shadow: 0 0 0 2px rgba(0, 58, 90, 0.1);
        }

        /* Results Styling */
        #spgs_calculation_result {
            margin-top: 25px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 4px solid #ae3331;
            overflow: hidden;
        }

        .result-section {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .result-section:last-child {
            border-bottom: none;
        }

        .result-section h3 {
            color: #333;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            position: relative;
            padding-left: 15px;
        }

        .result-section h3::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 16px;
            background: #ae3331;
            border-radius: 2px;
        }

        .bullet-point {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin: 10px 0;
            position: relative;
            padding-left: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .bullet-point:before {
            content: "•";
            position: absolute;
            left: 10px;
            color: #ae3331;
            font-size: 18px;
        }

        .age-details {
            padding: 15px;
            background: white;
            border-radius: 6px;
            margin: 10px 0;
        }

        .age-value {
            font-size: 18px;
            color: #ae3331;
            font-weight: 500;
            margin-top: 8px;
        }

        .academic-year {
            display: inline-block;
            background: #ae3331;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 5px;
        }

        @media screen and (max-width: 768px) {
            .modal-content {
                margin: 20px auto;
                padding: 20px;
                width: 95%;
            }
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery === 'undefined') {
                console.error('jQuery not loaded');
                return;
            }

            var $ = jQuery;
            
            // Modal functionality
            $('#openCalculator').on('click', function() {
                $('#calculatorModal').fadeIn(300);
            });

            $('.close-btn').on('click', function() {
                $('#calculatorModal').fadeOut(300);
            });

            // Close modal when clicking outside
            $(window).on('click', function(event) {
                if ($(event.target).is('#calculatorModal')) {
                    $('#calculatorModal').fadeOut(300);
                }
            });

            // Handle Escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('#calculatorModal').is(':visible')) {
                    $('#calculatorModal').fadeOut(300);
                }
            });

            // Calculator functionality
            $('#spgs_calculate_age').on('click', function() {
                var birthDate = $('#spgs_birth_date').val();
                var academicYear = $('#spgs_academic_year').val();

                if (!birthDate) {
                    alert('Please select date of birth');
                    return;
                }

                var cutoffDate = new Date(academicYear, 7, 31);
                var birthDateTime = new Date(birthDate);
                
                var ageYear = cutoffDate.getFullYear() - birthDateTime.getFullYear();
                var ageMonth = cutoffDate.getMonth() - birthDateTime.getMonth();
                var ageDay = cutoffDate.getDate() - birthDateTime.getDate();

                if (ageDay < 0) {
                    ageMonth--;
                    var lastMonth = new Date(cutoffDate.getFullYear(), cutoffDate.getMonth(), 0);
                    ageDay += lastMonth.getDate();
                }
                if (ageMonth < 0) {
                    ageYear--;
                    ageMonth += 12;
                }

                var yearGroup = '';
                var status = '';

                if (ageYear < 3) {
                    status = 'Too young for enrollment as per UK National guidelines.';
                } else if (ageYear >= 18) {
                    status = 'Not eligible to any standard as per UK National.';
                } else {
                    switch(ageYear) {
                        case 3: yearGroup = 'FS1'; break;
                        case 4: yearGroup = 'FS2'; break;
                        case 5: yearGroup = 'Year 1'; break;
                        case 6: yearGroup = 'Year 2'; break;
                        case 7: yearGroup = 'Year 3'; break;
                        case 8: yearGroup = 'Year 4'; break;
                        case 9: yearGroup = 'Year 5'; break;
                        case 10: yearGroup = 'Year 6'; break;
                        case 11: yearGroup = 'Year 7'; break;
                        case 12: yearGroup = 'Year 8'; break;
                        case 13: yearGroup = 'Year 9'; break;
                        case 14: yearGroup = 'Year 10'; break;
                        case 15: yearGroup = 'Year 11'; break;
                        case 16: yearGroup = 'Year 12'; break;
                        case 17: yearGroup = 'Year 13'; break;
                    }
                    status = 'Eligible for enrollment in ' + yearGroup + ' as per UK National guidelines.';
                }

                var resultHtml = `
                    <div class="result-section">
                        <h3>UK National Age Enrollment</h3>
                        <div class="bullet-point">
                            ${status}
                        </div>
                    </div>
                    <div class="result-section">
                        <h3>Age Calculation</h3>
                        <div class="age-details">
                            <span class="academic-year">Academic Year ${academicYear}-${parseInt(academicYear) + 1}</span>
                            <p style="margin-top: 15px;">Age as of 31st August ${academicYear}:</p>
                            <p class="age-value">${ageYear} Years, ${ageMonth} Months and ${ageDay} Days</p>
                        </div>
                    </div>
                `;

                $('#spgs_calculation_result').html(resultHtml).slideDown(300);
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }
    add_shortcode('age_calculator', 'spgs_age_calculator_shortcode');
}