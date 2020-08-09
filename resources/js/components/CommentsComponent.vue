<template>
    <div class="container">
        <h4 v-if='!comments'>No comments at this moment.</h4>
        <ul v-else>
            <li v-for="comment in comments" v-bind:key='comment.id'>{{comment.id}} : {{comment.content}}</li>
        </ul>
        <comment-form @load-overlay-comment='toggleLoadOverlay' :csrf="csrf" :saveCommentRoute="saveCommentRoute" @new-comment="addComment" :user="user" @show-login="$emit('show-login')"></comment-form>
    </div>
</template>

<script>
    import CommentForm from "./CommentForm";
    export default {
        name : 'CommentsComponent',
        components : {
            CommentForm
        },
        props : ['getCommentsRoute', 'saveCommentRoute', 'user', 'csrf'],
        data : function() {
            return {
                comments : null,
            }
        },
        created() {
            this.getComments();
        },
        methods : {
            getComments() {
                this.toggleLoadOverlay(true);
                let comments = {};
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
                    this.toggleLoadOverlay(false);
                    if(data.length > 0)
                        this.comments = comments;
                })
                .catch(error => {
                    this.$emit('toggle-load-overlay', false);
                    console.log(error);
                });
            },
            addComment(comment) {
                Vue.set(this.comments, comment.id, comment);
            },
            toggleLoadOverlay(displayOverlay) {
                this.$emit('toggle-load-overlay', displayOverlay);
            },
        }
    }
</script>
