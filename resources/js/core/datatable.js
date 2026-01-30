export class DataTableComponent {
    constructor({
        selector,
        url,
        columns,
        extraParams = () => ({}),
        options = {},
        onDraw = null
    }) {
        this.selector = selector;
        this.url = url;
        this.columns = columns;
        this.extraParams = extraParams;
        this.options = options;
        this.onDraw = onDraw;

        this.table = null;
        this.init();
    }

    init() {
        // console.log(this.extraParams)
        const self = this;

        this.table = $(this.selector).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: this.url,
                data(d) {
                    Object.assign(d, self.extraParams());
                }
            },
            columns: this.columns,
            language: window.datatableLanguage,
            dom: '<"d-block"r>t<"d-flex justify-content-end mt-3"p>',
            ...this.options,
            drawCallback() {
                if (typeof self.onDraw === 'function') {
                    self.onDraw(self.table);
                }
            }
        });
    }

    reload(reset = false) {
        if (this.table) {
            this.table.ajax.reload(null, reset);
        }
    }

    destroy() {
        if (this.table) {
            this.table.destroy(true);
        }
    }

    search(value) {
        if (this.table) {
            this.table.search(value);
        }
        return this;
    }

    draw(reset = false) {
        if (this.table) {
            this.table.draw(reset);
        }
    }
}
