<script>
    (function () {
        function showFeedback(data) {
            var el = document.getElementById('exercise-feedback');
            if (!el) return;
            el.classList.remove('hidden', 'border-medic/40', 'bg-medic/10', 'border-rescue/40', 'bg-rescue/10');
            el.classList.add(data.correct ? 'border-medic/40' : 'border-rescue/40', data.correct ? 'bg-medic/10' : 'bg-rescue/10');
            el.innerHTML = '<p class="font-bold ' + (data.correct ? 'text-medic-light' : 'text-red-200') + '">' + (data.correct ? 'Correct' : 'Not quite') + '</p><p class="mt-2 text-sm text-slate-200">' + data.explanation + '</p>';
        }

        document.querySelectorAll('[data-answer]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                fetch(window.SalusExercise.checkUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': window.SalusExercise.csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        scenario: window.SalusExercise.scenarioIndex,
                        answer: btn.dataset.answer,
                    }),
                })
                    .then(function (r) { return r.json(); })
                    .then(showFeedback);
            });
        });
    })();
</script>
