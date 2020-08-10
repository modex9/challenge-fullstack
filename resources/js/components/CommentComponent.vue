<template>
    <div class="container">
        <h4 v-if='!comments && !isChild'>No comments at this moment.</h4>
        <div  v-else v-for="comment in comments" v-bind:key='comment.id' :class="'comment-wrapper-' + comment.id">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{comment.user.name}}
                        <button v-if="user && comment.user && user.id == comment.user.id" :id="'comment-' + comment.id" class="btn btn-danger" @click="$emit('delete-comment')">Delete</button>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{'ID : ' + comment.id}}</p>
                            <p class="card-text">{{"parent ID: " + comment.parent}}</p>
                            {{comment.content}}
                        </div>
                        <a v-if="user" href="#" @click='getCommentId' :id="'replay-to-' + comment.id">Reply</a>
                        <comment-form v-if="replyCommentId == comment.id" @load-overlay-comment='toggleLoadOverlay' :csrf="csrf" :saveCommentRoute="saveCommentRoute"
                            @new-comment="addComment" :user="user"></comment-form>
                    </div>
                </div>
            </div>
            <comment-component v-if="comment['children']" :comments="comment['children']"
                 @delete-comment="deleteComment" :user="user" @load-overlay-comment="toggleLoadOverlay"></comment-component>
        </div>
    </div>
</template>

<script>
    import CommentForm from './CommentForm';
    export default {
        name : 'CommentComponent',
        components : { CommentForm }, 
        props : ['comments', 'user', 'saveCommentRoute', 'csrf'],
        data : function() {
            return {
                replyCommentId : 0,
            }
        },
        methods : {
            deleteComment() {
                this.$emit('delete-comment');
            },
            addComment(comment) {
                this.$emit('new-comment', comment);
            },
            toggleLoadOverlay(displayOverlay) {
                this.$emit('load-overlay-comment', displayOverlay);
            },
            getCommentId() {
                this.replyCommentId = event.target.getAttribute('id').split("-").pop();
            }
        }
    }
</script>
