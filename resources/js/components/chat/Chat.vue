<template>
   <div class="row">

       <div class="col-8">
           <div class="card card-default">
               <div class="card-header">Messages</div>
               <div class="card-body p-0">
                   <ul class="list-unstyled" style="height:300px; overflow-y:scroll">
                       <li class="p-2" v-for="(message, index) in messages" :key="index" >
                           <strong>{{ message.user.first_name }}</strong>
                           {{ message.body }}
                       </li>
                   </ul>
               </div>

               <input
                    @keydown="sendTypingEvent"
                    @keyup.enter="sendMessage"
                    v-model="newMessage"
                    type="text"
                    name="message"
                    placeholder="Enter your message..."
                    class="form-control">
           </div>
            <span class="text-muted" v-if="activeUser" >{{ activeUser.name }} is typing...</span>
       </div>

        <div class="col-4">
            <div class="card card-default">
                <div class="card-header">Active Users</div>
                <div class="card-body">
                    <ul>
                        <li class="py-2" v-for="(user, index) in users" :key="index">
                            {{ user.name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

   </div>
</template>

<script>
   
    export default {
        props:['user'],
        data: function () {
            return {
                messages: [],
                newMessage: '',
                users:[],
                activeUser: false,
                typingTimer: false,
            }
        },
        created() {
            this.fetchMessages();
	  	  Echo.private('chat')
            .listen('ChatEvent', (e) => {
		console.log(e)
                this.messages.push({
                    message: e.message.message,
                   
                });
            });
                
        },
        methods: {
           
            fetchMessages() {
                const currentUser = JSON.parse(localStorage.getItem("currentUser"));
                axios.get('/api/auth/admin/message',
		 { 
	          headers:
		    {
			Authorization: "Bearer " + currentUser.data.access_token
		    }
		    }		
		).then(response => {
                    this.messages = response.data.data;
                })
            },
            sendMessage() {
            const currentUser = JSON.parse(localStorage.getItem("currentUser"));
                this.messages.push({
                    user: 1,
                    message: this.newMessage,
		    receiver_id: 2
                });
                axios.post('/api/auth/admin/message', 
		{message: this.newMessage, receiver_id: 2},
		 { 
	          headers:
		    {
			Authorization: "Bearer " + currentUser.data.access_token
		    }
		    }
		).then(response => {

                    this.messages.push(response.data[0]);
		console.log(this.messages)
                });
                this.newMessage = '';
            },
            sendTypingEvent() {
                
            }
        }
    }
</script> 
