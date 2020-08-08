<template>
    <div class="container">
        <h4 v-if='!commnets'>No comments at this moment.</h4>
        <ul v-else>
            <li v-for="comment in comments">{{comment.content}}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        name : 'CommentsComponent',
        props : ['getRoute'],
        data : function() {
            return {
                comments : null,
                isLoading : true,
            }
        },
        created() {
            this.getComments();
        },
        methods : {
            getComments() {
                fetch(this.getRoute, {
                    method : 'GET',
                    headers : {
                        "Content-Type": "application/json; charset=utf-8",
                        'X-CSRF-TOKEN' : this.csrf,
                    },
                })
                .then(data => data.json())
                .then(data => {
                    this.comments = data['comments'];
                })
                .catch(errorr => console.log(errorr));
            },
        }
    }
</script>
