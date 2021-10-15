<template>

     <ul class="navbar-nav float-left mr-auto ml-3 pl-1 nav-left">
        <!-- Notification -->
            <!-- Notification -->
            <li class="nav-item dropdown">
                        <a @click="markNotificationAsRead()" class="nav-link dropdown-toggle pl-md-3 position-relative" href=""
                        id="bell" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <span><i data-feather="bell" class="svg-icon"></i></span>
                        <span class="badge badge-primary notify-no rounded-circle count" >{{ countUnread.length }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown" style="width:25rem">
                        
                        <ul class="list-style-none" v-if="countRead.length > 0" :style=' countRead.length > 5 ? "overflow-y: scroll;height:375px;" : "" '>
                            
                            <li v-for="count in countRead" :key="count.id">
                                <div class="message-center notifications position-relative">
                                    <!-- Message -->
                                    
                                    <a :href="count.data.type_notif == 'test' ? linkUrlNotificationTest(count) : linkUrlNotification(count) "
                                    class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                 
                                    <div class="w-75 d-inline-block v-middle pl-2">
                                        <h6 class="message-title mb-0 mt-1" v-html="count.data.message"></h6>
                                        <span class="font-12 text-nowrap d-block text-muted">{{ count.data.data_notif.title }}</span>
                                        <!-- <span class="font-12 text-nowrap d-block text-muted">{{ count.data.type }}</span> -->
                                        <span class="font-12 text-nowrap d-block text-muted">{{ new Date(count.data.data_notif.created_at ).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3")}}</span>
                                    </div>
                                    
                                    <div v-if="count.data.new == 'new'" class="float-right"
                                     style="
                                        position: relative;
                                        right: -53px;
                                        top: -20px;">
                                        <span class="badge badge-danger"><small>new</small></span>
                                    </div>
                                    </a>
                                </div>
                                
                            </li>
                            <li>
                                <a class="nav-link pt-3 text-center text-dark" href="">
                                    <strong><small>Check all notification</small></strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>
                        <div v-else>
                            <li>
                                <a class="nav-link pt-3 text-center text-dark" href="">
                                    <strong>Tidak ada notifikasi terbaru</strong>
                                </a>
                            </li>
                        </div>
                        
                    </div>
            </li>
        <!-- End Notification -->
    </ul>
</template>


<script>


export default {

    props:['unreads','userid','readnotifications'],
    
    data(){
        return {
            urlNotification : '',
            countUnread: this.unreads,
            countRead: this.readnotifications,
        }
    },
    watch: {
        $route : {
                immediate :true,
                handler() {
                    this.getCount();
                    this.getUnred();
                }
            }
        
    },
    mounted() {
        this.notifWatch();
    },
    methods:{
        linkUrlNotification(count){
            let urlNotif = '';
                urlNotif = '/siswa/work/'+count.data.lesson.slug+'/lesson/'+count.data.data_notif.id+'/work/preview';

                return urlNotif;
            
        },
        linkUrlNotificationTest(count){
            let urlNotifTest = '';
                urlNotifTest = '/siswa/test/lesson/'+count.data.lesson.slug;

                return urlNotifTest;
        },
        markNotificationAsRead() {
            axios
                .get('/siswa/markAsRead').then(() => {
                    this.countUnread = [];
            });
        },

        notifWatch(){
             Echo.private('App.Models.User.'+this.userid)
                .notification((notification) => {
                    
                let newUnreadNotifications = {
                    data:{
                        type_notif : notification.type_notif,
                        message : notification.message,
                        data_notif:notification.data_notif,
                        lesson:notification.lesson ,
                        user:notification.user,
                        new : 'new'
                    }
                };
                this.countRead.push(newUnreadNotifications);
                console.log(this.countRead);
                this.countRead.sort(function(a,b){
                    return new Date(b.data.data_notif.created_at) - new Date(a.data.data_notif.created_at);
                });
                
                this.countUnread.push(newUnreadNotifications);

            });
            
        },
        getCount(){
            
            axios
                .get('/siswa/notif/read').then((response) => {
                    this.countRead = response.data.data;
            });
        },
        getUnred(){
            axios
                .get('/siswa/notif/unread').then((response) => {
                    this.countUnread = response.data.data;
                    console.log(response.data.data.length);
                })
        }
    }
}
</script>