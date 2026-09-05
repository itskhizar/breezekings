$(document).ready(function() {
    var $totalMarksInput = $('input[name="total_marks"]');
    var $checkboxes = $('input[name="exercise_ids[]"]');

    function updateTotalMarks() {
        var selectedIds = [];
        $('input[name="exercise_ids[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            // Only clear if we are sure (optional, maybe user wants to manually set marks?)
            // User requirement seems to be auto-calculation.
            // Let's verify if we should overwrite manual input. 
            // The issue is "saved as 29 instead of 30". 
            // We should trust the server sum.
            $totalMarksInput.val(0);
            return;
        }

        $.ajax({
            url: 'ajax-get-exercises-total.php',
            type: 'POST',
            data: { ids: selectedIds },
            dataType: 'json',
            success: function(response) {
                console.log("Server calculated total: ", response.total);
                
                // Aggressively set the value
                $totalMarksInput.val(response.total);

                // Flash the input to show it updated
                $totalMarksInput.css('background-color', '#e8f0fe');
                setTimeout(function(){
                    $totalMarksInput.css('background-color', '');
                }, 500);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching max marks:", error);
            }
        });
    }

    // Bind to change event
    $(document).on('change', 'input[name="exercise_ids[]"]', function() {
        console.log("Exercise selection changed, recalculating...");
        updateTotalMarks();
    });

    // Initial check (in case page loads with checked boxes)
    if ($('input[name="exercise_ids[]"]:checked').length > 0) {
        updateTotalMarks();
    }
});
