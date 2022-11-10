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
            <div class="search-bar">
                <b-field>
                    <b-input type="text" expanded placeholder="Search Food here..."></b-input>
                    <p class="control">
                        <button class="button is-success">SEARCH</button>
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
                        <b-rate v-model="rate"></b-rate>
                    </div>
                    <div style="display: flex; flex-direction: column;">
                        <b-button class="button is-primary is-outlined my-2" icon-right="cart-plus">Add to Cart</b-button>
                        <b-button class="button is-primary">Buy Now</b-button>
                    </div>
                </div>
            </div>

        </div>
        

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
            perPage: 5,
            defaultSortDirection: 'asc',


            locale: undefined,
            isModalActive: false,

            rate: 4,

            products: [],

            search: {
                product: '',
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
            })
        }
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
        flex-wrap: wrap;
    }
   
    .product-item{
        padding: 15px;
        margin: 15px;
        border: 1px solid red;
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
