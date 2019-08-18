<template>
  <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                </div>
                 <div class="card-body">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <!-- small card -->
                                <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{totalAdmin}}</h3>

                                    <p>Admin</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <router-link to="/user" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </router-link>
                                </div>
                            </div>
                        <div class="col-lg-3 col-6">
                            <!-- small card -->
                            <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{totalCusomer}}</h3>

                                <p>Total Customer</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                           <router-link to="/customer" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                           </router-link>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small card -->
                            <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{totalSupplier}}</h3>

                                <p>Total Supplier</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-industry"></i>
                            </div>
                            <router-link to="/supplier" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small card -->
                            <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ totalCategory }}</h3>

                                <p>Total Category</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                           <router-link to="/category" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                           </router-link>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small card -->
                            <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{totalProduct}}</h3>

                                <p>Total Product</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <router-link to="/product" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <!-- small card -->
                            <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{totalPurchase}}</h3>

                                <p>Total Purchases</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <router-link to="/purchase" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>
                            </div>
                        </div>
                        <!-- ./col -->
                        
                        <div class="col-lg-3 col-6">
                            <!-- small card -->
                            <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ totalSales }}</h3>

                                <p>Sales</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <router-link to="/sales" class="small-box-footer">
                                More info <i class="fas fa-arrow-circle-right"></i>
                            </router-link>
                            </div>
                        </div>
                        <!-- ./col -->
                        </div>
                    </div>
                 </div>
            </div>
        </div>
  </div>
</template>

<script>
export default {
    data(){
        return{

            totalAdmin : null,
            totalCusomer : null,
            totalSupplier : null,
            totalCategory : null,
            totalProduct : null,
            totalPurchase : null,
            totalSales : null,
        }
    },
     mounted(){
            console.log('Component mounted.')
            this.getAdmin()
            this.getCustomer()
            this.getSupplier()
            this.getCategory()
            this.getProduct()
            this.getPurchases()
            this.getSales()
            //console.log(this.$profile.currentUser())
        },
        methods:{
            getAdmin(){
                axios.get('/api/user')
                    .then(res=>{
                        this.totalAdmin = res.data.meta.total
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            getCustomer(){
                axios.get('/api/customer')
                    .then(res=>{
                        this.totalCusomer = res.data.meta.total
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            getSupplier(){
                axios.get('/api/supplier')
                    .then(res=>{
                        this.totalSupplier = res.data.meta.total
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            getCategory(){
                axios.get('/api/category')
                    .then(res=>{
                        this.totalCategory = res.data.meta.total
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            getProduct(){
                axios.get('/api/product')
                    .then(res=>{
                        this.totalProduct = res.data.meta.total
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            getPurchases(){
                axios.get('/api/purchase')
                    .then(res=>{
                        this.totalPurchase = res.data.meta.total
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            getSales(){
                axios.get('/api/sales')
                    .then(res=>{
                        this.totalSales = res.data.meta.total
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            }
        }
}
</script>

<style>

</style>