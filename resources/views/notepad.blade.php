<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Online Notepad</title>
</head>

<style>
    .notepad {
        width: 100%;
        height: 90vh;
        padding: 15px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        background-color: #f8f9fa;
        font-family: monospace;
        font-size: 16px;
        line-height: 1.6;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        resize: none;
        outline: none;
    }

    .notepad:focus {
        border-color: #007bff;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
    }
</style>

<body>
    <textarea placeholder="Please Type Something here.." class="notepad" id="note" style="width: 100%; height: 100vh;">{{ $note->content ?? '' }}</textarea>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let key = "{{ $note->key ?? '' }}";
            $(".notepad").focus();
            let typingTimer; // Timer identifier
            const doneTypingInterval = 1000; // Time in ms (2 seconds)

            $("#note").on("input", function() {
                clearTimeout(typingTimer); // Clear the previous timer
                typingTimer = setTimeout(() => {
                    saveNote(); // Call the save function after 2s of no input
                }, doneTypingInterval);
            });

            function saveNote() {
                $.ajax({
                    url: "{{ route('save') }}",
                    type: "POST",
                    data: {
                        content: $("#note").val(),
                        key: key,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response.success);
                        if (response.success) {
                            // Animate the textarea border color
                            $("textarea").css("border", "2px solid green").animate({
                                opacity: 0.8
                            }, 200).animate({
                                opacity: 1
                            }, 200, function() {
                                // Revert back after a short delay
                                setTimeout(() => {
                                    $("textarea").css("border", "1px solid #ccc"); // Original border color
                                }, 1000);
                            });
                        } else {
                            alert("Something went wrong");
                        }
                    },
                    error: function(xhr) {
                        console.error("Error saving note:", xhr.responseText);
                    }
                });
            }

        });
    </script>



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>