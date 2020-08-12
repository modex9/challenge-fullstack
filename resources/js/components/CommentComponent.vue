<template>
    <div class="container">
        <h4 v-if='!comments && !isChild'>No comments at this moment.</h4>
        <div  v-else v-for="comment in comments" v-bind:key='comment.id' :class="'comment-wrapper-' + comment.id">
            <div v-if="comment" class="row justify-content-center">
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
                        <a v-if="user" href="#" @click='replyTrigger' :id="'replay-to-' + comment.id">Reply</a>
                        <comment-form v-if="repliedCommentId == comment.id" @load-overlay-comment="toggleLoadOverlay"
                         :csrf="csrf" :save-comment-route="saveCommentRoute" @new-comment="addComment" :user="user"
                         :replied-comment-id="repliedCommentId"></comment-form>
                    </div>
                </div>
            </div>
            <comment-component v-if="comment && comment['children']" :comments="comment['children']" :csrf="csrf" :save-comment-route="saveCommentRoute"
                 @delete-comment="deleteComment" :user="user" @load-overlay-comment="toggleLoadOverlay" @new-comment="addComment"
                  @reply-to-comment="sendRepliedCommentId" :commentID="repliedCommentId"></comment-component>
        </div>
    </div>
</template>

<script>
    import CommentForm from './CommentForm';
    export default {
        name : 'CommentComponent',
        components : { CommentForm }, 
        props : ['comments', 'user', 'saveCommentRoute', 'csrf', 'commentID'],
        data : function() {
            return {
                repliedCommentId : 0,
            }
        },
        watch : {
            commentID() {
                this.repliedCommentId = this.commentID;
            },
        },
        methods : {
            deleteComment() {
                this.$emit('delete-comment');
            },
            addComment(data) {
                this.$emit('new-comment', data);
            },
            toggleLoadOverlay(displayOverlay) {
                this.$emit('load-overlay-comment', displayOverlay);
            },
            sendRepliedCommentId(commentID) {
                this.repliedCommentId = commentID;
                this.$emit('reply-to-comment', commentID);
            },
            replyTrigger() {
                this.repliedCommentId = event.target.getAttribute('id').split("-").pop();
                this.$emit('reply-to-comment', this.repliedCommentId);
            }
        }
    }
</script>
