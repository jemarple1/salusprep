<script>
    (function () {
        function showFeedback(data) {
            var el = document.getElementById('exercise-feedback');
            if (!el) return;
            el.classList.remove('hidden', 'border-medic/40', 'bg-medic/10', 'border-rescue/40', 'bg-rescue/10');
            el.classList.add(data.correct ? 'border-medic/40' : 'border-rescue/40', data.correct ? 'bg-medic/10' : 'bg-rescue/10');
            el.innerHTML = '<p class="font-bold ' + (data.correct ? 'text-medic-light' : 'text-red-200') + '">' + (data.correct ? 'Correct' : 'Not quite') + '</p><p class="mt-2 text-sm text-slate-200">' + data.explanation + '</p>';
        }

        function submitAnswer(answer) {
            document.querySelectorAll('.pharma-yesno-btn').forEach(function (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'pointer-events-none');
            });

            fetch(window.SalusExercise.checkUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.SalusExercise.csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    scenario: window.SalusExercise.scenarioIndex,
                    answer: answer,
                }),
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    window.SalusExercise.afterCheck(data, showFeedback);
                })
                .finally(function () {
                    document.querySelectorAll('.pharma-yesno-btn').forEach(function (btn) {
                        btn.disabled = false;
                        btn.classList.remove('opacity-50', 'pointer-events-none');
                    });
                });
        }

        document.querySelectorAll('[data-answer]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                submitAnswer(btn.dataset.answer);
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            if (e.key === 'y' || e.key === 'Y') {
                e.preventDefault();
                submitAnswer('yes');
            }
            if (e.key === 'n' || e.key === 'N') {
                e.preventDefault();
                submitAnswer('no');
            }
        });
    })();
</script>
