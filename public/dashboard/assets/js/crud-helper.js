window.CRUDHelper = {
    instances: [],

    init: function (config) {
        const instance = Object.create(this.prototype);
        instance.config = config;
        instance.instanceId = 'crud_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

        this.instances.push(instance);

        instance.initDataTable();
        instance.initDeleteBtn();

        if (instance.config.edit) {
            instance.initEditBtn();
        }

        return instance;
    },

    prototype: {
        initDataTable: function () {
            $(this.config.tableSelector).DataTable({
                processing: true,
                serverSide: true,
                ajax: this.config.ajaxUrl,
                columns: this.config.columns,
                responsive: true,
            });
        },

        initDeleteBtn: function () {
            const self = this;

            $(document)
                .off("click.crud_" + this.instanceId, this.config.delete.selector)
                .on("click.crud_" + this.instanceId, this.config.delete.selector, function (e) {
                    const $table = $(self.config.tableSelector);
                    const $button = $(e.currentTarget);

                    if (!$table.find($button).length && !$button.closest($table.closest('.tab-pane')).length) {
                        return;
                    }

                    let $btn = $(e.currentTarget);
                    let id = $btn.data("id");
                    let originalHtml = $btn.html();

                    $btn.prop("disabled", true);
                    $btn.html(`<span class="spinner-border spinner-border-sm me-1"></span>${window.translations.loading || 'Loading...'}`);

                    Swal.fire({
                        title: window.translations.delete_confirm || "Are you sure?",
                        text: window.translations.delete_text || "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: window.translations.delete_btn || "Delete",
                        cancelButtonText: window.translations.cancel_btn || "Cancel",
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: window.translations.deleting_title || "Deleting...",
                                text: window.translations.deleting_wait || "Please wait",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => Swal.showLoading(),
                            });

                            $.ajax({
                                url: self.config.delete.url.replace(":id", id),
                                type: "DELETE",
                                data: { _token: $('meta[name="csrf-token"]').attr("content") },
                                success: () => {
                                    Swal.fire({
                                        title: window.translations.deleted_title || "Deleted!",
                                        text: window.translations.deleted_success || "Deleted successfully.",
                                        icon: "success",
                                    }).then(() => {
                                        $(self.config.tableSelector).DataTable().ajax.reload();
                                    });
                                },
                                error: () => {
                                    Swal.fire(
                                        window.translations.error_title || "Error!",
                                        window.translations.error_text || "There was a problem deleting the record.",
                                        "error"
                                    );
                                },
                                complete: () => {
                                    $btn.prop("disabled", false);
                                    $btn.html(originalHtml);
                                }
                            });
                        } else {
                            $btn.prop("disabled", false);
                            $btn.html(originalHtml);
                        }
                    });
                });
        },

        initEditBtn: function () {
            const self = this;

            $(document)
                .off("click.crud_edit_" + this.instanceId, this.config.edit.selector)
                .on("click.crud_edit_" + this.instanceId, this.config.edit.selector, function (e) {
                    const $table = $(self.config.tableSelector);
                    const $button = $(e.currentTarget);

                    if (!$table.find($button).length && !$button.closest($table.closest('.tab-pane')).length) {
                        return;
                    }

                    let $btn = $(e.currentTarget);
                    let id = $btn.data("id");
                    let originalHtml = $btn.html();

                    $btn.prop("disabled", true);
                    $btn.html(`<span class="spinner-border spinner-border-sm me-1"></span>${window.translations.loading || 'Loading...'}`);

                    $.ajax({
                        url: self.config.edit.fetchUrl.replace(":id", id),
                        type: "GET",
                        success: (data) => {
                            $(self.config.edit.formSelector).attr("action", self.config.edit.updateUrl.replace(":id", id));

                            $(`${self.config.edit.formSelector} input:not([type="file"]), ${self.config.edit.formSelector} textarea, ${self.config.edit.formSelector} select`).val('');
                            const currentImageSelector = self.config.edit.currentImageSelector || '#currentLogo';
                            $(currentImageSelector).html("");

                            for (const key in data) {
                                const $input = $(self.config.edit.formSelector + ' [name="' + key + '"]');
                                if ($input.length === 0) continue;

                                if ($input.attr("type") === "file") {
                                    $input.val("");
                                    const imageSelector = self.config.edit.currentImageSelector || '#currentLogo';
                                    if ((key === "logo" || key === "image") && data[key]) {
                                        $(imageSelector).html(`<img src="${data[key]}" alt="${key}" style="max-width:100px;">`);
                                    }
                                } else {
                                    $input.val(data[key]);
                                }
                            }

                            if (data.translations && typeof data.translations === 'object') {
                                for (const locale in data.translations) {
                                    for (const key in data.translations[locale]) {
                                        const selector = `${self.config.edit.formSelector} [name="translations[${locale}][${key}]"]`;
                                        const $translatedInput = $(selector);
                                        if ($translatedInput.length) {
                                            $translatedInput.val(data.translations[locale][key]);
                                        }
                                    }
                                }
                            }

                            if (typeof self.config.edit.beforeShowModal === "function") {
                                self.config.edit.beforeShowModal(data);
                            }

                            $(self.config.edit.modalSelector).modal("show");
                        },
                        error: () => {
                            toastr.error(window.translations.unexpected_error || 'Unexpected error');
                        },
                        complete: () => {
                            $btn.prop("disabled", false);
                            $btn.html(originalHtml);
                        }
                    });
                });
        }
    }
};
