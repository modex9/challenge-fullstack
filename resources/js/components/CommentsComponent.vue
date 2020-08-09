<template>
    <div class="container">
        <span v-for="error in errors" v-bind:key="error[0]" class="invalid-feedback" role="alert">
            <strong>{{ error[0] }}</strong>
        </span>
        <comment-form @load-overlay-comment='toggleLoadOverlay' :csrf="csrf" :saveCommentRoute="saveCommentRoute"
         @new-comment="addComment" :user="user" @show-login="$emit('show-login')"></comment-form>
         <comment-component :comments="comments" :is-child="false"></comment-component>
    </div>
</template>
:id="'comment-'comment.id"
<script>
    import CommentForm from "./CommentForm";
    import CommentComponent from "./CommentComponent";
    export default {
        name : 'CommentsComponent',
        components : {
            CommentForm, CommentComponent
        },
        props : ['getCommentsRoute', 'saveCommentRoute', 'user', 'csrf'],
        data : function() {
            return {
                comments : null,
                errors : null,
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
            deleteComment() {
                const commentID = event.target.getAttribute('id').split("-").pop();
                event.preventDefault();
                this.errors = {};
                this.toggleLoadOverlay(true);
                fetch(this.saveCommentRoute + '/' + commentID, {
                    method : 'DELETE',
                    headers : {
                        "Content-Type": "application/json; charset=utf-8",
                        'X-CSRF-TOKEN' : this.csrf,
                    },
                })
                .then(data => data.json())
                .then(data => {
                    if(!data['success'])
                        this.errors = data['errors'];
                    else {
                        Vue.delete(this.comments, data['comment_id']);
                    }
                    this.toggleLoadOverlay(false);
                })
                .catch(error => {

                    this.toggleLoadOverlay(false);
                    console.log(error);
                });
            },
        }
    }
</script>
