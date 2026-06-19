@props([
    'options',
    'pinnedFocus' => null,
])

@once
    <style>
        .focus-exam-option.is-selected[data-color="medic"] .focus-exam-surface { border-color: var(--color-medic); background-color: color-mix(in srgb, var(--color-medic) 15%, transparent); box-shadow: 0 0 0 2px color-mix(in srgb, var(--color-medic) 40%, transparent); }
        .focus-exam-option.is-selected[data-color="medic"] .focus-exam-check { border-color: var(--color-medic); background-color: var(--color-medic); color: var(--color-navy); }
        .focus-exam-option.is-selected[data-color="medic"] .focus-exam-check-icon { opacity: 1; }

        .focus-exam-option.is-selected[data-color="ems"] .focus-exam-surface { border-color: var(--color-ems); background-color: color-mix(in srgb, var(--color-ems) 15%, transparent); box-shadow: 0 0 0 2px color-mix(in srgb, var(--color-ems) 40%, transparent); }
        .focus-exam-option.is-selected[data-color="ems"] .focus-exam-check { border-color: var(--color-ems); background-color: var(--color-ems); color: var(--color-navy); }
        .focus-exam-option.is-selected[data-color="ems"] .focus-exam-check-icon { opacity: 1; }

        .focus-exam-option.is-selected[data-color="rescue"] .focus-exam-surface { border-color: var(--color-rescue); background-color: color-mix(in srgb, var(--color-rescue) 15%, transparent); box-shadow: 0 0 0 2px color-mix(in srgb, var(--color-rescue) 40%, transparent); }
        .focus-exam-option.is-selected[data-color="rescue"] .focus-exam-check { border-color: var(--color-rescue); background-color: var(--color-rescue); color: #fff; }
        .focus-exam-option.is-selected[data-color="rescue"] .focus-exam-check-icon { opacity: 1; }

        .focus-exam-option.is-selected[data-color="safety"] .focus-exam-surface { border-color: var(--color-safety); background-color: color-mix(in srgb, var(--color-safety) 15%, transparent); box-shadow: 0 0 0 2px color-mix(in srgb, var(--color-safety) 40%, transparent); }
        .focus-exam-option.is-selected[data-color="safety"] .focus-exam-check { border-color: var(--color-safety); background-color: var(--color-safety); color: var(--color-navy); }
        .focus-exam-option.is-selected[data-color="safety"] .focus-exam-check-icon { opacity: 1; }

        .focus-exam-option.is-selected[data-color="pharma"] .focus-exam-surface { border-color: var(--color-pharma); background-color: color-mix(in srgb, var(--color-pharma) 15%, transparent); box-shadow: 0 0 0 2px color-mix(in srgb, var(--color-pharma) 40%, transparent); }
        .focus-exam-option.is-selected[data-color="pharma"] .focus-exam-check { border-color: var(--color-pharma); background-color: var(--color-pharma); color: var(--color-navy); }
        .focus-exam-option.is-selected[data-color="pharma"] .focus-exam-check-icon { opacity: 1; }
    </style>
@endonce

<div
    class="focus-exam-picker"
    data-focus-picker
    data-save-url="{{ route('platform.paywall.focus', $sectionSlug) }}"
    data-csrf="{{ csrf_token() }}"
    {{ $attributes->class('') }}
>
    <div class="grid gap-4 lg:grid-cols-3">
        @foreach ($options as $stat)
            <x-focus-exam-card
                :category="$stat->category"
                :accuracy="$stat->accuracy_percent"
                :selected="$pinnedFocus === $stat->category"
            />
        @endforeach
    </div>
</div>

@once
    <script>
        (function () {
            if (window.__focusExamPickerInit) return;
            window.__focusExamPickerInit = true;

            function syncPicker(picker) {
                var options = picker.querySelectorAll('[data-focus-option]');
                var anyChecked = false;

                options.forEach(function (label) {
                    var input = label.querySelector('input');
                    if (input.checked) {
                        anyChecked = true;
                    }
                    label.classList.toggle('is-selected', input.checked);
                });

                if (!anyChecked && options.length > 0) {
                    var first = options[0].querySelector('input');
                    first.checked = true;
                    options[0].classList.add('is-selected');
                    saveSelection(picker, first.value);
                }
            }

            function saveSelection(picker, category) {
                var formData = new FormData();
                formData.append('_token', picker.dataset.csrf);
                formData.append('category', category);

                fetch(picker.dataset.saveUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                }).catch(function () {});
            }

            document.addEventListener('change', function (event) {
                var input = event.target;
                if (!input.classList.contains('focus-exam-radio')) return;

                var picker = input.closest('[data-focus-picker]');
                if (!picker) return;

                syncPicker(picker);
                saveSelection(picker, input.value);
            });

            function initFocusPickers() {
                document.querySelectorAll('[data-focus-picker]').forEach(syncPicker);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initFocusPickers);
            } else {
                initFocusPickers();
            }
        })();
    </script>
@endonce
