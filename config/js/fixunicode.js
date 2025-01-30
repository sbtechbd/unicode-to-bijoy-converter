function FixUnicode() {
            const text = document.getElementById("EDT").value;
            const fixedText = text.replace(/[\u09DC\u09DD]/g, match => {
                // Replace broken characters
                if (match === '\u09DC') return 'ড়'; // Example fix
                if (match === '\u09DD') return 'ঢ়';
                return match;
            });
            document.getElementById("CONVERTEDT").value = fixedText;
        }


        