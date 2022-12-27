<template>
    <div>
        <div class="mynav">
            <div class="mynav-brand">{{userRole}}</div>
            <div class="burger-button" @click="open = true">
                <div class="burger-div"></div>
                <div class="burger-div"></div>
                <div class="burger-div"></div>
            </div>
        </div>

        <b-sidebar
            :mobile="mobile"
            type="is-light"
            :fullheight="fullheight"
            :fullwidth="fullwidth"
            :overlay="overlay"
            :expand-on-hover="expandOnHover"
            :reduce="reduce"
            :right="right"
            v-model="open">
            <div class="p-4">
                <h3 class="title is-4"></h3>

                <b-menu class="is-custom-mobile">

                    <b-menu-list label="Menu">
                        <b-menu-item icon="home" label="Home" tag="a" href="/vendor/dashboard"></b-menu-item>
                        <b-menu-item icon="storefront-outline" label="My Stores" tag="a" href="/vendor/my-store"></b-menu-item>
                        <b-menu-item icon="storefront-outline" label="My Orders" tag="a" href="/vendor/my-orders"></b-menu-item>

                        <b-menu-item icon="sitemap-outline" label="Products" tag="a" href="/vendor/all-products"></b-menu-item>
                        <b-menu-item icon="adjust" label="Adjustment List" tag="a" href="/vendor/product-adjustment-list"></b-menu-item>
                        <b-menu-item icon="laptop" label="POS" tag="a" href="/vendor/pos"></b-menu-item>
                        <b-menu-item icon="laptop" label="Sales Report" tag="a" href="/vendor/sales-report"></b-menu-item>

                        <!--                        <b-menu-item icon="math-log" label="Product Logs" tag="a" href="/vendor/product-logs-list"></b-menu-item>-->

                    </b-menu-list>


                    <b-menu-list label="Actions">
                        <b-menu-item @click="logout" label="Logout"></b-menu-item>
                    </b-menu-list>
                </b-menu>
            </div>
        </b-sidebar>

    </div>


</template>

<script>
export default {
    data(){
        return{
            open: false,
            overlay: true,
            fullheight: true,
            fullwidth: false,
            right: true,
            expandOnHover: true,
            reduce: false,
            mobile: "reduce",

            user: {
                role: '',
                lname: '',
                fname: '',
                mname: '',
            },
        }
    },
    methods: {
        logout(){
            axios.post('/logout').then(()=>{
                window.location = '/';
            })
        },

        loadUser(){
            axios.get('/load-user').then(res=>{
                this.user = res.data;
            })
        }
    },

    mounted(){
        this.loadUser();
    },

    computed: {
        userRole(){
            return this.user.role.toUpperCase();
        }
    }
}
</script>

<style scoped>


.logo{
    padding: 0 30px 0 30px;
    height: 90px;
}

.burger-div{
    width: 20px;
    height: 3px;
    background-color: #696969;
    margin: 0 0 3px 0;
    margin-left: auto;
    border-radius: 10px;
}

.burger-button{
    display: flex;
    flex-direction: column;
    margin-left: auto;
}

.mynav{
    padding: 25px;
    border-bottom: 2px solid rgba(196, 196, 196, 0.53);
    display: flex;
}

.mynav-brand{
    font-weight: bold;
    font-size: 1.2em;
}

/* .hero{
      background-image: url("/img/bg-hero.jpg");
      background-repeat: no-repeat;
      background-size: cover;
  } */


</style>
