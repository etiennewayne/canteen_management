<template>
    <div>
        <b-field>
            <b-button class="button is-primary" label="Browse Product" @click="openModal">...</b-button>
        </b-field>


        <b-modal v-model="this.isModalActive" has-modal-card
                 trap-focus scroll="keep" aria-role="dialog" aria-modal>
            <div class="modal-card card-width">
                <header class="modal-card-head">
                    <p class="modal-card-title">Select Product</p>
                    <button type="button" class="delete"
                            @click="isModalActive = false" />

                </header>

                <section class="modal-card-body">
                    <div>

                        <b-field label="Search" label-position="on-border" >
                            <b-input type="text" v-model="search.product" placeholder="Search Product..." expanded auto-focus></b-input>
                            <p class="control">
                                <b-button class="is-primary" icon-pack="fa" icon-left="search" @click="loadAsyncData"></b-button>
                            </p>
                        </b-field>

                        <div class="table-container">
                            <b-table
                                :data='data'
                                :loading="loading"
                                paginated
                                backend-pagination
                                :total='total'
                                :per-page="perPage"
                                @page-change="onPageChange"
                                detail-transition=""
                                aria-next-label="Next page"
                                aria-previous-label="Previouse page"
                                aria-page-label="Page"
                                :show-detail-icon=true
                                aria-current-label="Current page"
                                default-sort-direction="defualtSortDirection"
                                @sort="onSort">

                                <b-table-column field="product_id" label="ID" v-slot="props">
                                    {{props.row.product_id}}
                                </b-table-column>

                                <b-table-column field="product" label="Product" v-slot="props">
                                    {{props.row.product}}
                                </b-table-column>

                                <b-table-column field="qty" label="Quantity" v-slot="props">
                                    {{props.row.qty}}
                                </b-table-column>


                                <b-table-column field="" label="Action" v-slot="props">
                                    <div class="buttons">
                                        <b-button class="is-small is-warning" icon-right="arrow-down" @click="selectData(props.row)"></b-button>
                                    </div>
                                </b-table-column>
                            </b-table>
                        </div>

                    </div>
                </section>

                <footer class="modal-card-foot">
                    <b-button
                        label="Close"
                        @click="isModalActive=false"></b-button>
                </footer>
            </div>
        </b-modal>


    </div>
</template>

<script>
export default {
    props: {
        propProduct: {
            type: String,
            default: '',
        },
        propStoreId: {
            type: Number,
            default: '',
        }

    },



    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortfield: 'product_id',
            sortOrder:'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection:'',

            isModalActive: false,
            errors:{},

            product: {
                product: '',
            },

            search: {
                product: '',
            },

        }
    },
    methods: {
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortfield}.${this.sortOrder}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`,
                `product=${this.search.product}`,
                `storeid=${this.propStoreId}`,
            ].join('&');

            this.loading = true;
            axios.get(`/vendor/get-product-lists?${params}`).then(({data}) => {
                this.data = [];
                let currentTotal = data.total;
                if (data.total / this.perPage > 1000) {
                    currentTotal = this.perPage * 1000;
                }

                this.total = currentTotal;
                data.data.forEach((item) => {
                    this.data.push(item);
                });

                this.loading = false;
            }).catch(err => {
                this.data = [];
                this.total = 0;
                this.loading = false;
                throw err;
            });

        },

        onPageChange(page) {
            this.page = page;
            this.loadAsyncData();
        },

        onSort(field, order) {
            this.sortfield = field;
            this.sortOrder = order;
            this.loadAsyncData();
        },

        setPerPage() {
            this.loadAsyncData();
        },

        openModal(){
             this.loadAsyncData();
             this.isModalActive = true;
        },

        selectData(dataRow){
            this.isModalActive = false;
            this.$emit('browseProduct', dataRow);
        }


    },

    computed: {
        valueProduct(){
            return this.propProduct;
        },




    },

}
</script>

<style scoped>
    .card-width{
        width: 640px;
    }
</style>
