        // Function to fix broken Bijoy text
        function FixBijoy() {
            const text = document.getElementById("EDT").value;
            const fixedText = text.replace(/(Ñ|Û|Ü|ß)/g, match => {
                // Replace broken characters
                if (match === 'Ñ') return 'ড';
                if (match === 'Û') return 'ঢ';
                if (match === 'Ü') return 'ণ';
                if (match === 'ß') return 'ঃ';
                return match;
            });
            document.getElementById("CONVERTEDT").value = fixedText;
        }