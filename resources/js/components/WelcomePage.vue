<template>
    <div>
        <!-- <div class="banner">
            <div class="banner-text">
                FOOD ORDERING MANAGEMENT SYSTEM
            </div>
            <div class="banner-img-container">
                <img src="/img/banner-logo.jpg" class="banner-img"/>
            </div>
        </div>   -->




        <div class="product-section">
            <div class="ml-5">
                <a href="/my-cart" v-if="countCart > 0">
                    <b-icon icon="cart-outline"></b-icon>
                    <span class="cart-no">{{  this.countCart }}</span>
                </a>
            </div>

            <div class="search-bar">
                <b-field>
                    <b-input type="text" expanded
                        placeholder="Search Food here..."
                        v-model="search.product"
                        @keydown.native.enter="loadProducts"></b-input>
                    <p class="control">
                        <button class="button is-primary" @click="loadProducts">SEARCH</button>
                    </p>
                </b-field>
            </div>

            <div class="products-container">
                <div class="product-item" v-for="(item, index) in products.data" :key="index">
                    <div class="product-img-container">
                        <img class="product-img" :src="`/storage/products/${item.product_img_path}`" />
                    </div>
                    <div class="product-title">
                        {{ item.product }}
                    </div>
                    <div class="product-title">
                        {{ item.store }}
                    </div>
                    <div>
                        <strong>Quantity: </strong> {{  item.qty }}
                    </div>
                    <div class="product-price">
                        P{{ item.product_price | formatPrice }}
                    </div>
<!--                    <div>-->
<!--                        <b-field>-->
<!--                            <b-numberinput min="0" controls-position="compact" v-model="item.purchase_qty"></b-numberinput>-->
<!--                        </b-field>-->
<!--                    </div>-->
                    <div class="product-rating">
                        <b-rate
                            disabled
                            show-score
                            v-model="item.total_rates"></b-rate>
                    </div>
                    <div class="product-footer">
                        <div v-if="propIsAuth == 1">
                            <b-button class="button is-primary is-fullwidth is-outlined my-2"
                                icon-right="cart-plus"
                                @click="openAddCart(item)"
                            >
                                    Add to Cart
                            </b-button>
                            <b-button
                                class="button is-primary is-fullwidth"
                                tag="a" :href="`/buy-now/${item.product_id}`"
                            >
                                Buy Now
                            </b-button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pagination-container">
                <b-pagination
                    :total="total"
                    :per-page="perPage"
                    v-model="current"
                    :icon-prev="prevIcon"
                    :icon-next="nextIcon"
                    aria-next-label="Next page"
                    aria-previous-label="Previous page"
                    aria-page-label="Page"
                    aria-current-label="Current page"
                    @change="onPageChange">
                </b-pagination>
            </div>

        </div>


        <!--modal add to cart-->
        <b-modal v-model="modalAddToCart" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="addToCart">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Add To Cart</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalAddToCart = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <div class="modal-item-container">
                                        <div class="product-img-container">
                                            <img class="product-img" :src="`/storage/products/${cart.product_img_path}`" />
                                        </div>
                                        <div class="modal-item-info ml-4">
                                            <div>
                                                <strong>Product: </strong> {{ cart.product }}
                                            </div>
                                            <div>
                                                <strong>Price: </strong> {{  cart.product_price | formatPrice }}
                                            </div>
                                            <div>
                                                <strong>Rate: </strong>
                                                <b-rate
                                                disabled
                                                v-model="cart.total_rates"></b-rate>
                                            </div>
                                            <div>
                                                <strong>Quantity: </strong>
                                                <b-numberinput v-model="cartFields.qty"
                                                    size="is-small" min="1" controls-position="compact"></b-numberinput>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button
                            :class="btnClass"
                            label="Save">Add to Cart</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->




    </div> <!--root div-->
</template>

<script>

export default {
    props: ['propIsAuth'],
    data(){
        return{

            total: 0,
            loading: false,
            sortField: 'product_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 10,
            defaultSortDirection: 'asc',
            prevIcon: 'chevron-left',
            nextIcon: 'chevron-right',
            current: 1,


            locale: undefined,
            isModalActive: false,

            rate: 4,

            products: [],

            search: {
                product: '',
            },

            cartFields: {
                qty: 1
            },

            modalAddToCart: false,
            cart: {},
            countCart: 0,
            btnClass: {
                'is-info': true,
                'button': true,
                'is-loading':false,
            },

        }

    },

    methods: {

        loadProducts(){
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `product=${this.search.product}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            axios.get(`/welcome-page-load-all-products?${params}`).then(res => {
                this.products = res.data;
                this.total = res.data.total

                console.log(res.data)
            })
        },

        loadCart(){
            axios.get('/get-count-cart-items').then(res=>{
                this.countCart = parseFloat(res.data)
            }).catch(err=>{

            })
        },

        onPageChange(page) {
            this.page = page
            this.loadProducts()
        },

        openAddCart(product){
            this.cart = product
            this.modalAddToCart = true;

        },
        addToCart(){
            this.cartFields.product_id = this.cart.product_id;
            this.cartFields.price = this.cart.product_price;

            axios.post('/my-cart', this.cartFields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        type: 'is-success',
                        title: 'Added to Cart!',
                        message: 'Successfully added to cart.',
                    });
                    this.loadCart();
                }

                this.modalAddToCart = false;
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            })
        }
    },

    mounted() {
        this.loadProducts();
        this.loadCart();
    },


}
</script>

<style scoped src="../../css/welcome.css">
</style>
