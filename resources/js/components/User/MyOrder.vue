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

                                <b-table-column field="product_order_id" label="ID" sortable v-slot="props">
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
    </div>    
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
            }
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