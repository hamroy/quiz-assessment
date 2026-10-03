/**
 * Register the reusable `x-confirm` directive and its shared modal store.
 *
 * Alpine is bundled and exposed as `window.Alpine` by Livewire. We hook into
 * Alpine's `alpine:init` event (dispatched at Alpine.start()) to register the
 * store and directive before the page initializes.
 */
document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;

    Alpine.store('confirm', {
        open: false,
        title: '',
        message: '',
        variant: 'danger',
        onConfirm: null,

        show(options = {}) {
            this.title = options.title ?? 'Are you sure?';
            this.message = options.message ?? '';
            this.variant = options.variant ?? 'danger';
            this.onConfirm = options.onConfirm ?? null;
            this.open = true;
        },

        confirm() {
            const callback = this.onConfirm;
            this.open = false;
            this.onConfirm = null;
            callback?.();
        },

        cancel() {
            this.open = false;
            this.onConfirm = null;
        },

        buttonClasses() {
            return ({
                danger: 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
                warning: 'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-500',
                primary: 'bg-brand-600 hover:bg-brand-700 focus:ring-brand-500',
                info: 'bg-sky-600 hover:bg-sky-700 focus:ring-sky-500',
            })[this.variant] ?? 'bg-red-600 hover:bg-red-700 focus:ring-red-500';
        },
    });

    Alpine.directive('confirm', (el, { expression }, { evaluate }) => {
        const resolve = () => {
            try {
                return evaluate(expression);
            } catch {
                return expression;
            }
        };

        el.addEventListener('click', (event) => {
            // Second click after confirmation: let the original handler run.
            if (el._confirmed) {
                el._confirmed = false;
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();

            const value = resolve();

            Alpine.store('confirm').show({
                title: el.getAttribute('x-confirm-title') ?? undefined,
                message: typeof value === 'string' ? value : (value.message ?? ''),
                variant: el.getAttribute('x-confirm-variant')
                    ?? (typeof value === 'object' ? value.variant : undefined)
                    ?? 'danger',
                onConfirm: () => {
                    el._confirmed = true;
                    el.click();
                },
            });
        });
    });
});
