<template>
<div class="container">
        <div class="row justify-content-center">
         
                         <div class="col-md-8 mt-4">
                            <!-- Widget: user widget style 2 -->
                            <div class="card card-widget widget-user-2">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-warning">
                                <div class="widget-user-image">
                                <img class="img-circle elevation-2" :src="'./img/a1.png'" alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username"> {{ user.name }}</h3>
                                <h5 class="widget-user-desc">Software Engenner</h5>
                            </div>
                            <div class="card-footer p-0">
                                <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    Name : {{ user.name }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    Email : {{ user.email }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    Created At : {{ user.created_at | mydate }}
                                    </a>
                                </li>
                                
                                </ul>
                            </div>
                            </div>
                            <!-- /.widget-user -->
                        </div>
                     
            
        </div>
</div> 

    
</template>

<script>
export default {
    data(){
        return{
            user : []
        }
    },
    mounted(){
        //console.log(user)
        this.user = user
    }
}
</script>

<style>

</style>