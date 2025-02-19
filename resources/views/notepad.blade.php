<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Online Notepad</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Prism.js CSS (for code highlighting) -->
    <link href="{{ asset('css/prism.css') }}" rel="stylesheet">

    <style>
        ::-webkit-scrollbar {
            width: 3px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px grey;
            border-radius: 10px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #007bff;
            border-radius: 10px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #007bff;
        }

        body {
            background-color: #1e1e1e;
            color: #fff;
            font-family: 'Courier New', monospace;
        }

        .editor-container {
            width: 100%;
            height: 90vh;
            position: relative;
            border-radius: 3px;
            border: 1px solid #444;
            background-color: #282c34;
            padding: 6px;
            overflow: hidden;
        }

        /* Hidden textarea for saving content */
        .hidden-textarea {
            display: none;
        }

        /* Styled contenteditable div for Prism.js */
        .editor {
            width: 100%;
            height: 100%;
            overflow-y: auto;
            white-space: pre-wrap;
            outline: none;
            caret-color: white;
            font-size: 16px;
        }

        /* Make sure pre tags look like code */
        pre {
            margin: 0;
            background: transparent;
            border: none;
        }

        /* Add focus effect */
        .editor-container:focus-within {
            border: 1px solid #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        }
    </style>
</head>

<body>

    <div class="container mt-3">
        <h3 class="text-center">Online Notepad</h3>
        <div class="editor-container">
            <div class="editor" contenteditable="true" id="editor">{!! $note->content !!}</div>
            <textarea id="hidden-textarea" class="hidden-textarea"></textarea>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Prism.js -->
    <script src="{{ asset('js/prism.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script> --}}

    <script>
        $(document).ready(function() {
            let key = "{{ $note->key ?? '' }}";
            let typingTimer;
            const doneTypingInterval = 1000;

            $(".editor").on("input", function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(saveNote, doneTypingInterval);
                $("#hidden-textarea").val($(this).html());
            });

            function saveNote() {
                $.ajax({
                    url: "{{ route('save') }}",
                    type: "POST",
                    data: {
                        content: $("#hidden-textarea").val(),
                        key: key,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response.success);
                        if (response.success) {
                            $(".editor-container").css("border", "2px solid green").animate({
                                opacity: 0.8
                            }, 200).animate({
                                opacity: 1
                            }, 200, function() {
                                setTimeout(() => {
                                    $(".editor-container").css("border",
                                        "1px solid #444");
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

</body>

</html>
