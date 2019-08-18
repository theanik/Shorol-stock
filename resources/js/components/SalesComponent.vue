<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sales <span class="p-1 mb-2 bg-primary text-white">{{ total }}</span></h4> 
                        <div class="card-tools" style="position: absolute;right: 1rem;top: .5rem,color:#fff;">
                        <button @click="csvExport(csvData)" class="btn btn-primary">Export as CVS</button>
                        <button type="button" class="btn btn-success" @click.prevent="create">
                           Sale
                            <i class="fas fa-plus"></i>
                        </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-2">
                                <strong>Search By :</strong>
                                </div>
                                <div class="col-md-3">
                                <select class="form-control" id="fileds">
                                    <option value="name">Name</option>
                                    <option value="company_name">Company</option>
                                    <option value="email">Enail</option>
                                    <option value="phone">Phone</option>
                                </select>
                                </div>
                                <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="Search">
                                </div>
                                <div class="col-md-2">
                                     <button type="button" class="btn btn-primary" >
                                        Reset Search
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- table  -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Sale Id</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Customer Name</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="sale in sales" :key="sale.id">
                                        <th scope="row">{{ sale.id }}</th>
                                        <td><a @click.prevent="showProductDetails(sale.product_id)" 
                                        class="text-primary" style="cursor:pointer">{{ sale.name }}</a></td>
                                        <td><a @click.prevent="showCustomerDetails(sale.customer_id)"
                                        class="text-primary" style="cursor:pointer">{{ sale.c_name}}</a></td>
                                        <td>{{ sale.quantity }}</td>
                                        <td>{{ sale.t_price | currency}}</td>
                                        
                                        <td>
                                            <button type="button" @click.prevent="show(sale)" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" @click.prevent="destroy(sale)" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td v-show="!sales.length" colspan="6">
                                            <div class="alert alert-danger" role="alert">Sorry :( No data found.</div>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                                </table>
                                <pagination-component v-if="pagination.last_page > 1"
                                    :pagination="pagination"
                                    :offset="5"
                                    @paginate="getSales"></pagination-component>
                        </div>

                        <!-- end table -->
                    </div>
                </div>
            </div>
            <!-- Modal -->
                <div class="modal fade" id="ModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">

                            <h5 v-if="showMode == true" class="modal-title" id="exampleModalLongTitle">Details for <strong>{{ showDetails.name }}</strong> </h5>
                            <h5 v-else class="modal-title" id="exampleModalLongTitle">{{ editMode ? "Edit" : "Add New"}}  Customer</h5>
                            
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form v-if="showMode == false" @submit.prevent="store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                          <alert-error :form="form" message="There were some problems with your input."></alert-error>
                                
                                <div class="form-group">
                                <label>Select Product </label>
                                    <select v-model="form.purchases_id" name="purchases_id" required 
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('purchases_id') }" placeholder="Select Category">
                                        <option v-for="purchase in purchases" :key="purchase.id" v-bind:value="purchase.id">{{ purchase.product.name }}</option>
                                    </select>
                                <has-error :form="form" field="purchases_id"></has-error>
                                </div>

                                <div class="form-group">
                                <label>Select Customer </label>
                                    <select v-model="form.customer_id" name="customer_id" required 
                                        class="form-control" :class="{ 'is-invalid': form.errors.has('customer_id') }" placeholder="Select Category">
                                        <option v-for="customer in customers" :key="customer.id" v-bind:value="customer.id">{{ customer.c_name }}</option>
                                    </select>
                                <has-error :form="form" field="customer_id"></has-error>
                                </div>

                                <div class="form-group">
                                <label>Quantity</label>
                                <input v-model="form.quantity" type="number" name="quantity" placeholder="Enater E-mail"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('quantity') }">
                                <has-error :form="form" field="quantity"></has-error>
                                </div>
                                
                               

                           
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">Save</button>
                        </div>
                         </form>

                          <div v-if="showMode ==  true" class="modal-body">

                              <div class="card border-info mb-3">
                                <div class="card-body">

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Porduct Name : </strong> {{ showDetails.name }}</li>
                                        <li class="list-group-item"><strong>Porduct Code : </strong> {{ showDetails.code }}</li>
                                        <li class="list-group-item"><strong>Retail Price : </strong> {{ showDetails.retail_price | currency}}</li>
                                        <li class="list-group-item"><strong>Sale Price: </strong> {{ showDetails.sale_price | currency}}</li>
                                        <li class="list-group-item"><strong>Quantity : </strong> {{ showDetails.quantity }}</li>
                                        <li class="list-group-item"><strong>Total Ammount : </strong> {{ showDetails.t_price | currency}}  
                                        ({{ showDetails.sale_price }} X {{ showDetails.quantity }})
                                        </li>
                                        <hr>
                                        <li class="list-group-item"><strong>Customer Name : </strong> {{ showDetails.c_name }}</li>
                                        <li class="list-group-item"><strong>Customer Email : </strong> {{ showDetails.email }}</li>
                                        <li class="list-group-item"><strong>Customer Phone : </strong> {{ showDetails.phone }}</li>
                                        <li class="list-group-item"><strong>Created At : </strong> {{ showDetails.created_at | mydate }}</li>
                                    </ul>
                                </div>
                                </div>
                          </div>
                        </div>
                    </div>
                </div>
            <!-- end modal  -->
            <!-- moadal 2  -->
            <div class="modal fade" id="ModalLong2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="exampleModalLongTitle">{{ showDetails.name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- show Single Product Details -->
                          <div v-if="showProMode ==  true" class="modal-body">

                              <div class="card border-info mb-3">
                                <div class="card-body">

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Product Name : </strong> {{ showDetails.name }}</li>
                                        <li class="list-group-item"> <strong>Category Name : </strong> {{ showDetails.cat_name }}</li>
                                        <li class="list-group-item"><strong>Product Code : </strong> {{ showDetails.code }}</li>
                                        <li class="list-group-item"><strong>Created At : </strong> {{ showDetails.created_at | mydate }}</li>
                                        
                                    </ul>
                                </div>
                                </div>
                          </div>
                          <!-- show single Supplier Detais -->
                          <div v-if="showCustomerMode ==  true" class="modal-body">

                              <div class="card border-info mb-3">
                                <div class="card-body">

                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Customer Name : </strong> {{ showDetails.c_name }}</li>
                                        <li class="list-group-item"><strong>Customer Company : </strong> {{ showDetails.company_name }}</li>
                                        <li class="list-group-item"><strong>Phone : </strong> {{ showDetails.phone }}</li>
                                        <li class="list-group-item"><strong>E-mail : </strong> {{ showDetails.email }}</li>
                                        <li class="list-group-item"><strong>Created At : </strong> {{ showDetails.created_at | mydate }}</li>
                                        
                                    </ul>
                                </div>
                                </div>
                          </div>
                          <!--  -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        
                    </div>
                    </div>
                </div>
            </div>
            <!-- moadal 2 end -->
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                sales : [],
                editMode : false,
                showMode : false,
                purchases : [],
                customers : [],
                form : new Form({
                    id : '',
                    purchases_id : '',
                    customer_id : '',
                    quantity : ''
                    
                }),
                total : null,
                showDetails : [],
                showProMode : false,
                showCustomerMode : false,
                data :{
                    exportdata : []
                },
                message : '',
                pagination:{
                    
                },
                current_page : 1

            }
        },computed: {
            csvData() {
            return this.exportdata.map(item => ({
                ...item,
            }));
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.getSales()
            this.getProduct()
            this.getCustomer()
            this.getData()
        },
        methods : {
            csvExport(arrData) {
                    let csvContent = "data:text/csv;charset=utf-8,";
                    csvContent += [
                        Object.keys(arrData[0]).join(";"),
                        ...arrData.map(item => Object.values(item).join(";"))
                    ]
                        .join("\n")
                        .replace(/(^\[)|(\]$)/gm, "");

                    const data = encodeURI(csvContent);
                    const link = document.createElement("a");
                    link.setAttribute("href", data);
                    link.setAttribute("download", "sales.csv");
                    link.click();
                    
                },
            getSales(){
                this.$Progress.start()
                axios.get('/api/salesdata?page='+this.pagination.current_page)
                    .then(res=>{
                        this.exportdata = res.data.data
                        this.sales = res.data.data
                        this.pagination = res.data
                        this.current_page = res.data.current_page
                        this.$Progress.finish()
                    })
                    .catch(e=>{
                        console.log(e)
                        this.$Progress.fail()
                    })
            },
            getData(){
                axios.get('/api/sales')
                .then(res=>{
                        //console.log(res.data)
                        //this.sales = res.data.data
                        //this.pagination = res.data.meta
                        this.total = res.data.meta.total
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            create(){
                this.showMode = false
                this.form.reset()
                this.form.clear()
                $('#ModalLong').modal('show');
            },
            store(){
                this.$Progress.start()
                this.form.busy = true
                this.form.post('/api/sales')
                    .then(res=>{
                        this.getSales()
                       // console.log(res.data.message)
                        this.message = res.message
                        if(res.data.message){
                            this.$Progress.fail()
                            this.$snotify.error('Product Quantity is out Srock ','Error')
                        }else{
                            $('#ModalLong').modal('hide');
                            if(this.form.successful){
                                this.$Progress.finish();
                                this.$snotify.success('Product Add Successfully','Success')
                            }else{
                                this.$Progress.fail()
                                this.$snotify.error('Something wend wrong. Try aging','Error')
                            }
                        }
                       
                    })
                    .catch(e=>{
                        this.$Progress.fail()
                        console.log(e)
                    })
            },
            getProduct(){
                 axios.get('/api/allpurchase')
                    .then(res=>{
                        //console.log(res.data.data)
                        this.purchases = res.data.data
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            getCustomer(){
                 axios.get('/api/allcustomer')
                    .then(res=>{
                        //console.log(res.data.data)
                        this.customers = res.data.data
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            show(sale){
                this.showMode = true
                $('#ModalLong').modal('show');
                //console.log(product)
                this.showDetails = sale
            },
            destroy(sale){

                this.$snotify.clear()
                this.$snotify.confirm("You will not be able to recover this data!","Are you sure?", {
                        showProgressBar: false,
                        closeOnClick: false,
                        pauseOnHover: true,
                        buttons:[
                            {
                                text : "Yes",
                                action : toast => {
                                    this.$snotify.remove(toast.id)
                                    //console.log(product)
                                    this.$Progress.start()
                                    axios.delete('/api/sales/'+sale.id)
                                    .then(res=>{
                                        this.getSales()
                                            this.$Progress.finish();
                                            this.$snotify.success('Sale Data Delete','Success')
                                       
                                    })
                                    .catch(e=>{
                                        this.$Progress.fail()
                                        console.log(e)
                                    })
                                },
                                bold : true
                            },
                            {
                                text : "No",
                                action : toast => {
                                    this.$snotify.remove(toast.id)
                                },
                                bold : true
                            }
                            
                        ]
                    })
            },
            showProductDetails(product_id){
                this.editMode = false
                this.showMode = false
                this.showCustomerMode = false
                this.showProMode = true
                $('#ModalLong2').modal('show');
                axios.get('/api/product/'+product_id)
                .then(res=>{
                        this.showDetails = res.data.data
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
            showCustomerDetails(customer_id){
                this.editMode = false
                this.showMode = false
                this.showProMode = false
                this.showCustomerMode = true
                $('#ModalLong2').modal('show');
                axios.get('/api/customer/'+customer_id)
                .then(res=>{
                        this.showDetails = res.data.data
                    })
                    .catch(e=>{
                        console.log(e)
                    })
            },
        }
    }
</script>
