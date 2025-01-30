// Function to start voice dictation
        function startDictation() {
            if (window.hasOwnProperty('webkitSpeechRecognition')) {
                var recognition = new webkitSpeechRecognition();
                recognition.continuous = true; // Enable long-time dictation
                recognition.interimResults = true; // Show partial results while speaking
                recognition.lang = "bn-BD"; // Set language to Bangla (Bangladesh)

                recognition.start();

                recognition.onresult = function(e) {
                    let transcript = '';
                    for (let i = e.resultIndex; i < e.results.length; ++i) {
                        transcript += e.results[i][0].transcript;
                    }
                    document.getElementById('EDT').value += transcript + ' ';
                };

                recognition.onerror = function(e) {
                    console.error('Speech recognition error:', e.error);
                    recognition.stop();
                };

                recognition.onend = function() {
                    console.log('Speech recognition ended. Restarting...');
                    recognition.start(); // Automatically restart for continuous listening
                };
            } else {
                alert("Your browser does not support voice recognition.");
            }
        }