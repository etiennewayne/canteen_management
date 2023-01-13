<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-10-desktop">
                    <div class="box">

                        <div class="is-flex mb-5" style="font-size: 20px; font-weight: bold;">PRODUCT LIST</div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Page" label-position="on-border">
                                    <b-select v-model="perPage" @input="setPerPage">
                                        <option value="5">5 per page</option>
                                        <option value="10">10 per page</option>
                                        <option value="15">15 per page</option>
                                        <option value="20">20 per page</option>
                                    </b-select>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="Search" label-position="on-border">
                                    <b-input type="text"
                                        v-model="search.product" placeholder="Search Product"
                                        @keyup.native.enter="loadAsyncData"/>
                                    <p class="control">
                                        <b-tooltip label="Search" type="is-success">
                                            <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                        </b-tooltip>
                                    </p>
                                </b-field>
                            </div>
                        </div>

                        <!-- <div class="columns">
                            <div class="column">
                                <b-field label="Store" label-position="on-border">
                                    <b-select v-model="search.store" @input="loadAsyncData">
                                        <option v-for="(item, index) in stores" :key="index" :value="item.store">{{  item.store }}</option>
                                    </b-select>
                                </b-field>
                            </div>
                        </div> -->

<!--                        <div class="buttons mt-3">-->
<!--                            <b-button icon-left="plus" class="is-primary">NEW</b-button>-->
<!--                        </div>-->

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

                            <b-table-column field="user_id" label="ID" sortable v-slot="props">
                                {{ props.row.product_id }}
                            </b-table-column>

                            <b-table-column field="store" label="Store" sortable v-slot="props">
                                {{ props.row.store.store }}
                            </b-table-column>

                            <b-table-column field="product" label="Product" sortable v-slot="props">
                                {{ props.row.product }}
                            </b-table-column>

                            <b-table-column field="product_total_rating" label="Ratings" sortable v-slot="props">
                                <b-rate show-score 
                                    disabled
                                    v-model="props.row.product_total_rating">
                                </b-rate>
                            </b-table-column>

                            <b-table-column field="qty" label="Quantity" centered sortable v-slot="props">
                                {{ props.row.qty }}
                            </b-table-column>

<!--                            <b-table-column field="is_inv" label="Inventoriable" centered v-slot="props">-->
<!--                                <span v-if="props.row.is_inv == 1">Yes</span>-->
<!--                                <span v-else>No</span>-->
<!--                            </b-table-column>-->

<!--                            <b-table-column field="is_available" label="Available" centered v-slot="props">-->
<!--                                <span v-if="props.row.is_available == 1">Yes</span>-->
<!--                                <span v-else>No</span>-->
<!--                            </b-table-column>-->

                            <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                    <b-tooltip label="Adjust Item" type="is-info">
                                        <b-button class="button is-small mr-1 is-info" tag="a" icon-right="adjust" @click="openModalAdjustment(props.row)"></b-button>
                                    </b-tooltip>
                                    <!-- <b-tooltip label="Delete" type="is-danger">
                                        <b-button class="button is-small mr-1" icon-right="delete" @click="confirmDelete(props.row.product_id)"></b-button>
                                    </b-tooltip> -->

                                </div>
                            </b-table-column>
                        </b-table>

                        <div>
                            <strong>Total: </strong> {{  total }} rows.
                        </div>

                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->




        <!--modal reset password-->
        <b-modal v-model="modalAdjustment" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="adjustItem">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Adjust Inventory</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalAdjustment = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Product" label-position="on-border"
                                            :type="this.errors.product ? 'is-danger':''"
                                            :message="this.errors.product ? this.errors.product[0] : ''">
                                        <b-input type="text" v-model="fields.product" placeholder="Product" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div><!--cols-->

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Adjustment Type" label-position="on-border" expanded
                                        :type="this.errors.adjustment_type ? 'is-danger':''"
                                        :message="this.errors.adjustment_type ? this.errors.adjustment_type[0] : ''">
                                        <b-select type="text" v-model="fields.adjustment_type" placeholder="Adjustment Type"
                                            expanded required>
                                            <option value="ADD">ADD</option>
                                            <option value="SUBSTRACT">SUBSTRACT</option>
                                        </b-select>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Quantity" label-position="on-border"
                                        :type="this.errors.qty ? 'is-danger':''"
                                        :message="this.errors.qty ? this.errors.qty[0] : ''">
                                        <b-numberinput v-model="fields.qty" controls-position="compact" min="1"></b-numberinput>
                                    </b-field>
                                </div>
                            </div><!--cols -->
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Remarks" label-position="on-border"
                                        :type="this.errors.remarks ? 'is-danger':''"
                                        :message="this.errors.remarks ? this.errors.remarks[0] : ''">
                                        <b-input type="textarea" v-model="fields.remarks" placeholder="Remarks"></b-input>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">

                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">Submit Adjustment</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->


    </div>
</template>

<script>

export default{
    props: ['propStores'],

    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'product_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 10,
            defaultSortDirection: 'asc',


            global_id : 0,

            search: {
                store: '',
                product: '',
            },

            modalAdjustment: false,

            fields: {},
            errors: {},

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
                `store=${this.search.store}`,
                `product=${this.search.product}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/vendor/get-all-products?${params}`)
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
                })
                .catch((error) => {
                    this.data = []
                    this.total = 0
                    this.loading = false
                    throw error
                })
        },
        /*
        * Handle page-change event
        */
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

        openModalAdjustment(row){
            this.modalAdjustment = true;
            this.fields = {
                product_id: row.product_id,
                product: row.product,
                qty: 1,
            };
            this.errors = {};
        },

        adjustItem: function(){

            axios.post('/vendor/adjust-item/' + this.fields.product_id, this.fields).then(res=>{
                if(res.data.status === 'adjusted'){
                    this.$buefy.dialog.alert({
                        title: 'Adjusted!',
                        message: 'Product successfully adjusted.',
                        type: 'is-success',
                        confirmText: 'OK',
                        onConfirm: () => {
                            this.modalAdjustment = false;
                            this.loadAsyncData();
                            this.clearFields();
                        }
                    })
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            });

        },


        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete user account?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/users/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        clearFields(){
            this.fields = {};
        },


        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;


            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/vendor/get-product-info/'+data_id).then(res=>{

            });
        },


        initData(){
            this.stores = JSON.parse(this.propStores)
        }
    },

    mounted() {
        //this.loadOffices();
        this.loadAsyncData();
        this.initData();
    }
}
</script>


<style>

    .table > tbody > tr {
        /* background-color: blue; */
        transition: background-color 0.5s ease;
    }

    .table > tbody > tr:hover {
        background-color: rgb(233, 233, 233);

    }

</style>
