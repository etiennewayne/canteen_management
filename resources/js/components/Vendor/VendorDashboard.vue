<template>
    <div>
        <div class="section">
            <div class="is-size-5 has-text-weight-bold border-line">
                MENU
            </div>

            <div class="menu">
                <div class="menu-box">
                    <a href="/vendor/dashboard">HOME</a>
                </div>
                <div class="menu-box">
                    <a href="/vendor/my-store">MY STORE</a>
                </div>
                <div class="menu-box">
                    <a href="/vendor/my-products">PRODUCTS</a>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

</script>


<style scoped>
    .border-line{
        border-bottom: 1px solid gray;
    }

    .menu{
        display: flex;
        flex-wrap: wrap;

    }

    .menu-box{
        width: 200px;
        padding: 15px;
        margin: 15px;
        height: 200px;
        font-weight: bold;
        text-align: center;
        border: 1px solid green;
    }
</style>
