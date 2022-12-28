<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="box">
                        <div class="box-header">
                            My Store Order
                        </div>

                        <div class="columns mt-5">
                            <div class="column">
                                <b-field label="Store" label-position="on-border">
                                    <b-select v-model="search.storeid">
                                        <option v-for="(item, index) in stores" :key="index" :value="item.store_id">{{ item.store }}</option>
                                    </b-select>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="Order Id" label-position="on-border">
                                    <b-input type="text" v-model="search.product_order_id" @keyup.native.enter="loadAsyncData" placeholder="Order Id">
                                    </b-input>
                                </b-field>
                            </div>
                        </div>

                        <div class="buttons">
                            <button class="button is-info" @click="loadAsyncData">
                                <b-icon icon="magnify"></b-icon>
                                &nbsp; Search
                            </button>
                        </div>

                        <div class="box-body">
                            <b-table
                                :data="data"
                                :loading="loading"
                                paginated
                                backend-pagination
                                :total="total"
                                :pagination-rounded="true"
                                :per-page="perPage"
                                @page-change="onPageChange"
                                aria-next-label="Next page"
                                aria-previous-label="Previous page"
                                aria-page-label="Page"
                                aria-current-label="Current page"
                                backend-sorting
                                :default-sort-direction="defaultSortDirection"
                                @sort="onSort">

                                <b-table-column field="product_order_id" label="Order Id" sortable v-slot="props">
                                    {{ props.row.product_order_id }}
                                </b-table-column>

                                <b-table-column field="username" label="Product" sortable v-slot="props">
                                    {{ props.row.product }}
                                </b-table-column>

                                <b-table-column field="is_delivered" label="Delivered" centered sortable v-slot="props">
                                    <span v-if="props.row.is_delivered">Yes</span>
                                    <span v-else>No</span>
                                </b-table-column>

                                <b-table-column field="delivery_type" label="Type" centered v-slot="props">
                                    {{ props.row.delivery_type }}
                                </b-table-column>
                                <b-table-column field="qty" label="Qty" centered v-slot="props">
                                    {{ props.row.purchase_qty }}
                                </b-table-column>

                                <b-table-column field="price" label="Total Price" centered v-slot="props">
                                    {{ props.row.price | formatPrice }}
                                </b-table-column>

                                <b-table-column field="date_order" label="Date Order" v-slot="props">
                                    {{ props.row.date_order }}
                                </b-table-column>

                                <b-table-column label="Action" v-slot="props">
                                    <div class="is-flex">
                                        <b-tooltip label="Mini POS" type="is-warning">
                                            <b-button class="button is-info mr-1 is-small" tag="a" icon-right="desktop-classic" @click="openModalMiniPOS(props.row)"></b-button>
                                        </b-tooltip>

                                    </div>
                                </b-table-column>

                            </b-table>
                        </div><!-- box-body -->
                    </div> <!-- box-->
                </div> <!-- col -->
            </div> <!-- cols -->
        </div>


        <!--modal create-->
        <b-modal v-model="modalMiniPOS" has-modal-card
             trap-focus
             :width="640"
             aria-role="dialog"
             aria-label="Modal"
             aria-modal>


            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Mini POS</p>
                    <button
                        type="button"
                        class="delete"
                        @click="modalMiniPOS = false"/>
                </header>

                <section class="modal-card-body">
                    <div class="">

                        <div class="columns">
                            <div class="column">
                                <b-field label="Customer Name">
                                    <b-input type="text" placeholder="Customer Name" v-model="fields.customer_name"/>
                                </b-field>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Quantity">
                                    <b-input type="text" placeholder="Quantity" readonly v-model="fields.purchase_qty"/>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="Price">
                                    <b-input type="text" placeholder="Price" readonly v-model="fields.price"/>
                                </b-field>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Tendered Cash"
                                         :type="this.errors.tendered_cash ? 'is-danger':''"
                                         :message="this.errors.tendered_cash ? this.errors.tendered_cash[0] : ''">
                                    <b-numberinput placeholder="Tendered Cash" :controls="false"
                                                   @keyup.native.enter="calculateChange"
                                                   v-model="fields.tendered_cash"></b-numberinput>
                                </b-field>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Change"
                                         :type="this.errors.change ? 'is-danger':''"
                                         :message="this.errors.change ? this.errors.change[0] : ''">
                                    <b-numberinput placeholder="Change"
                                                   :editable="false"
                                                   :controls="false" v-model="change"></b-numberinput>
                                </b-field>
                            </div>
                        </div>

                    </div>
                </section>
                <footer class="modal-card-foot">
                    <b-button
                        :class="btnClass"
                        label="Submit"
                        @click="submitSale"></b-button>
                </footer>
            </div>

        </b-modal>
        <!--close modal-->

    </div> <!--root div-->
</template>

<script>
export default{
    props: ['propStores'],
    data(){
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'product_order_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',

            search: {
                product: '',
                storeid: 0,
                product_order_id: '',
            },

            fields: {
                tendered_cash: 0,
                change : 0,
            },

            totalAmount: 0,
            change: 0,

            errors: {},

            modalMiniPOS: false,

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            stores: [],

        }
    },
    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `product=${this.search.product}`,
                `storeid=${this.search.storeid}`,
                `product_order_id=${this.search.product_order_id}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true;

            axios.get(`/vendor/get-vendor-my-orders?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                    data.data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.data.push(item)
                    })
                    this.loading = false
                }).catch((error) => {
                this.data = []
                this.total = 0
                this.loading = false
                throw error
            })
        },

        /* Handle page-change event */
        onPageChange(page) {
            this.page = page
            this.loadAsyncData()
        },

        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },

        setPerPage(){
            this.loadAsyncData()
        },

        openModalMiniPOS(row){
            this.clearFields()
            this.modalMiniPOS = true;
            this.totalAmount = row.price
            this.fields.product_id = row.product_id;
            this.fields.product = row.product;
            this.fields.order_type = row.delivery_type
            this.fields.purchase_qty = row.purchase_qty
            this.fields.product_order_id = row.product_order_id;
            this.fields.price = row.price;
            console.log(row)

        },

        clearFields(){
            this.fields = {
                tendered_cash: 0,
                change : 0,
            };
        },

        calculateChange(){
            this.change = this.fields.tendered_cash  - this.totalAmount
        },

        submitSale(){

            this.modalMiniPOS = false;
            this.fields.change = this.change;

            axios.post('/vendor/store-order', this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'RATED!',
                        message: 'The product was rated successfully.',
                        type: 'is-success',
                        onConfirm: () => {
                            this.clearFields();
                            this.loadAsyncData()
                        }
                    })
                }
            }).catch(err=>{
            })
        },

        initData(){
            this.stores = JSON.parse(this.propStores)
        }


    },

    mounted(){
        this.loadAsyncData();
        this.initData()
    }
}
</script>

<style scoped>
.box-header{
    font-weight: bold;
    font-size: 1.2em;
}
</style>
