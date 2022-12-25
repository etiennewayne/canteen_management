<template>
    <div>
        <div class="banner">
            <div class="banner-text">
                FOOD ORDERING MANAGEMENT SYSTEM
            </div>
            <div class="banner-img-container">
                <img src="/img/banner-logo.jpg" class="banner-img"/>
            </div>
        </div>  


      

        <div class="product-section">
            <div class="ml-5">
                <a href="/my-cart">
                    <b-icon icon="cart-outline"></b-icon>
                    <span class="cart-no">3</span>
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
                    <div class="product-price">
                        P{{ item.product_price | formatPrice }}
                    </div>
                    <div class="product-rating">
                        <b-rate 
                            disabled
                            v-model="item.total_rates"></b-rate>
                    </div>
                    <div class="product-footer">
                        <b-button class="button is-primary is-outlined my-2" icon-right="cart-plus" @click="openAddCart(item)">Add to Cart</b-button>
                        <b-button class="button is-primary" tag="a" :href="`/buy-now/${item.product_id}`">Buy Now</b-button>
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

            <form @submit.prevent="modalAddToCart">
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
                                                <b-numberinput v-model="cartFields.qty"></b-numberinput>
                                            </div>
                                        </div>
                                    </div>
                                    


                                    {{  this.cart }}
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
    props: ['propUser'],
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
                qty: 0
            },
           
            modalAddToCart: false,
            cart: {},
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
        onPageChange(page) {
            this.page = page
            this.loadProducts()
        },

        openAddCart(product){
            this.cart = product
            this.modalAddToCart = true;

        },
    },

    mounted() {
        this.loadProducts();

    },


}
</script>

<style scoped>

    .banner{
        margin: auto;
        max-width: 1024px;
        height: 400px;
        display: flex;
    }
    .banner-text{
        font-weight: bold;
        margin: auto;
        font-size: 2.0em;
    }
    .banner-img-container{
        height: 500px;
        width: 500px;
        position:relative;
        overflow:hidden;
    }
    .banner-img{
        position:absolute;
        top:0;
        bottom:0;
        margin: auto;
        width:100%;
    }


    .product-section{
        max-width: 1024px;
        margin: 20px auto;
    }
    .search-bar{
        margin: 15px;
    }
    .products-container{
        display: flex;
        justify-content: center;
        flex-wrap: wrap;

    }

    .product-item{
        padding: 15px;
        margin: 15px;
        max-width: 185px;
        border: 1px solid red;
        position: relative;
    }

    .product-img-container{
        height: 150px;
        width: 150px;
        position:relative;
        overflow:hidden;
    }
    .product-img{
        position:absolute;
        top:0;
        bottom:0;
        margin: auto;
        width:100%;
    }
    .product-title{
        font-weight: bold;
    }
    .product-rating{
        position: relative;
    }
    .product-footer{
        display: flex;
        flex-direction: column;
        /*position: absolute;*/
        /*bottom: 0;*/
    }

    .pagination-container{
        margin: 0 50px;
    }
    .modal-item-container{
        display: flex;
    }

    .cart-no{
        background: red;
        padding: 2px 5px;
        color: white;
        font-weight: bold;
        border-radius: 20px;
    }


    @media only screen and (max-width: 1024px) {
        .banner-text{
            font-weight: bold;
            margin: auto 0 auto 15px;
            font-size: 2.0em;
        }
    }

    @media only screen and (max-width: 768px) {
        .banner{
            display: flex;
            flex-direction: column;
        }
        .banner-text{
            margin: auto;
        }
        .banner-img-container{
            height: 500px;
            width: 500px;
            position:relative;
            overflow:hidden;
            margin: auto;
        }
        .banner-img{
            position:absolute;
            top:0;
            bottom:0;
            margin: auto;
            width:100%;
        }
    }

    @media only screen and (max-width: 480px) {
        .banner{
            display: flex;
            flex-direction: column;
        }
        .banner-text{
            margin: auto;
            text-align: center;
        }
        .banner-img-container{
            width: 500px;
        }

        .product-item{
            padding: 15px;
            margin: 15px auto;
        }
    }





</style>
