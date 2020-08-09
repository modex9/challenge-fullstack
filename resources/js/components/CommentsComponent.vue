<template>
    <div class="container">
        <h4 v-if='!comments'>No comments at this moment.</h4>
        <ul v-else>
            <li v-for="comment in comments">{{comment.content}}</li>
        </ul>
        <comment-form :csrf="csrf" :saveCommentRoute="saveCommentRoute" @new-comment="addComment" :user="user" @show-login="$emit('show-login')"></comment-form>
    </div>
</template>

<script>
    import CommentForm from "./CommentForm";
    export default {
        name : 'CommentsComponent',
        components : {
            CommentForm
        },
        props : ['getCommentsRoute', 'saveCommentRoute', 'user'],
        data : function() {
            return {
                csrf : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                comments : null,
            }
        },
        created() {
            this.getComments();
        },
        methods : {
            getComments() {
                fetch(this.getCommentsRoute, {
                    method : 'GET',
                    headers : {
                        "Content-Type": "application/json; charset=utf-8",
                        'X-CSRF-TOKEN' : this.csrf,
                    },
                })
                .then(data => data.json())
                .then(data => {
                    data.forEach(val => {
                            comments[val.id] = val;
                    });
                })
                .catch(error => console.log(error));
            },
            addComment(comment) {
                this.comments[commnet.id] = comment;
            },
        }
    }
</script>
