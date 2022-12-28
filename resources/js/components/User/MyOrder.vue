<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="box">
                        <div class="box-header">
                            Order History
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

                                <b-table-column field="store" label="Store" sortable v-slot="props">
                                    {{ props.row.product.store.store }}
                                </b-table-column>

                                <b-table-column field="username" label="Product" sortable v-slot="props">
                                    {{ props.row.product.product }}
                                </b-table-column>

                                <b-table-column field="is_delivered" label="Delivered" centered sortable v-slot="props">
                                    <span v-if="props.row.is_delivered">Yes</span>
                                    <span v-else>No</span>
                                </b-table-column>

                                <b-table-column field="delivery_type" label="Type" centered v-slot="props">
                                    {{ props.row.delivery_type }}
                                </b-table-column>
                                <b-table-column field="qty" label="Qty" centered v-slot="props">
                                    {{ props.row.qty }}
                                </b-table-column>

                                <b-table-column field="price" label="Price" centered v-slot="props">
                                    {{ props.row.price | formatPrice }}
                                </b-table-column>

                                <b-table-column field="date_order" label="Date Order" v-slot="props">
                                    {{ props.row.date_order }}
                                </b-table-column>

                               <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                    <b-tooltip label="Rate" type="is-warning">
                                        <b-button class="button is-info mr-1" tag="a" icon-right="star" @click="rateProduct(props.row.product_id)"></b-button>
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
         <b-modal v-model="modalRating" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submitRating">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Rate Product</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalRating = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="How's the product?"
                                             :type="this.errors.rating ? 'is-danger':''"
                                             :message="this.errors.rating ? this.errors.rating[0] : ''">
                                        <b-rate class="" icon="emoticon-happy-outline" v-model="fields.rating">
                                        </b-rate>
                                    </b-field>
                                </div>
                            </div>

                        </div>
                    </section>
                    <footer class="modal-card-foot">

                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">Save Rating</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->

    </div> <!--root div-->
</template>

<script>
export default{
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
            },

            fields: {
                product_id: 0,
            },
            errors: {},

            modalRating: false,

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },


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
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true;

            axios.get(`/get-my-orders?${params}`)
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

        rateProduct(dataId){
            this.modalRating = true;
            this.fields.product_id = dataId;
        },

        clearFields(){
            this.fields = {
                rating: 0,
                product_id : 0,
            };
        },

        submitRating(){
            this.modalRating = false;

            axios.post('/submit-product-rating', this.fields).then(res=>{
                if(res.data.status === 'submitted'){
                    this.$buefy.dialog.alert({
                        title: 'RATED!',
                        message: 'The product was rated successfully.',
                        type: 'is-success',
                        onConfirm: () => {
                            this.clearFields();
                        }
                    })
                }
            }).catch(err=>{
                if(err.response.data.status === 'exist'){
                    this.$buefy.dialog.alert({
                        title: 'RATED!',
                        type: 'is-danger',
                        message: 'Already rated this product.',
                    });

                    this.fields.rating = 0
                }
            })
        }


    },

    mounted(){
        this.loadAsyncData();
    }
}
</script>

<style scoped>
    .box-header{
        font-weight: bold;
        font-size: 1.2em;
    }
</style>
