import React, { useState, useEffect } from 'react';
import axios from 'axios';

const CommentList = ({ postId }) => {
  const [comments, setComments] = useState([]);
  const [newComment, setNewComment] = useState('');

  const [editingCommentId, setEditingCommentId] = useState(null);
  const [editContent, setEditContent] = useState('');

  const currentUser = JSON.parse(localStorage.getItem('user')) || null;

  useEffect(() => {
    fetchComments();
  }, [postId]);

  const fetchComments = () => {
    axios
      .get(`http://localhost:8000/api/comments/get_comments.php?post_id=${postId}`)
      .then((res) => setComments(res.data))
      .catch((err) => console.error('Error fetching comments:', err));
  };

  const handleAddComment = async () => {
    if (!newComment.trim()) return;
    try {
      const token = localStorage.getItem('token');
      await axios.post(
        'http://localhost:8000/api/comments/add_comment.php',
        { post_id: postId, content: newComment.trim() },
        {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
          },
        }
      );
      setNewComment('');
      fetchComments();
    } catch (err) {
      console.error('Error adding comment:', err);
    }
  };

  const startEditing = (commentId, currentContent) => {
    setEditingCommentId(commentId);
    setEditContent(currentContent);
  };

  const saveEdit = async (commentId) => {
    try {
      const token = localStorage.getItem('token');
      await axios.put(
        'http://localhost:8000/api/comments/update_comment.php',
        { comment_id: commentId, content: editContent.trim() },
        {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
          },
        }
      );
      setEditingCommentId(null);
      setEditContent('');
      fetchComments();
    } catch (err) {
      console.error('Error updating comment:', err);
    }
  };

  const cancelEdit = () => {
    setEditingCommentId(null);
    setEditContent('');
  };

  const handleDeleteComment = async (commentId) => {
    try {
      const token = localStorage.getItem('token');
      await axios.delete('http://localhost:8000/api/comments/delete_comment.php', {
        headers: { Authorization: `Bearer ${token}` },
        data: { comment_id: commentId },
      });
      fetchComments();
    } catch (err) {
      console.error('Error deleting comment:', err);
    }
  };

  // Single "Like" button for each comment
  const handleToggleCommentLike = async (commentId) => {
    if (!currentUser) {
      alert('Please log in to like/unlike this comment');
      return;
    }
    try {
      const token = localStorage.getItem('token');
      await axios.post(
        'http://localhost:8000/api/comments/toggle_comment_like.php', 
        { comment_id: commentId },
        {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        }
      );
      fetchComments();
    } catch (error) {
      console.error('Error toggling comment like:', error);
    }
  };
  

  return (
    <div className="mt-3">
      <h4 className="font-bold">Comments:</h4>
      {comments.map((c) => (
        <div key={c.id} className="border p-2 my-2 rounded">
          {editingCommentId === c.id ? (
            <textarea
              className="w-full p-2 border rounded mb-1"
              value={editContent}
              onChange={(e) => setEditContent(e.target.value)}
            />
          ) : (
            <p>{c.content}</p>
          )}

          <small className="text-gray-500">
            Posted by {c.author} on {new Date(c.created_at).toLocaleString()}
          </small>

          {/* If user is owner, can edit/delete */}
          {currentUser && Number(c.user_id) === Number(currentUser.id) && (
            <div className="mt-1">
              {editingCommentId === c.id ? (
                <>
                  <button
                    onClick={() => saveEdit(c.id)}
                    className="text-green-500 hover:underline mr-2"
                  >
                    Save
                  </button>
                  <button
                    onClick={cancelEdit}
                    className="text-gray-500 hover:underline"
                  >
                    Cancel
                  </button>
                </>
              ) : (
                <>
                  <button
                    onClick={() => startEditing(c.id, c.content)}
                    className="text-blue-500 hover:underline mr-2"
                  >
                    Edit
                  </button>
                  <button
                    onClick={() => handleDeleteComment(c.id)}
                    className="text-red-500 hover:underline"
                  >
                    Delete
                  </button>
                </>
              )}
            </div>
          )}

          <div className="mt-1">
            {currentUser && (
              <button
                onClick={() => handleToggleCommentLike(c.id)}
                className="text-emerald-500 hover:underline"
              >
                Like {c.like_count}
              </button>
            )}
          </div>
        </div>
      ))}

      {/* Add new comment */}
      <div className="mt-2">
        <textarea
          className="w-full p-2 border rounded"
          value={newComment}
          onChange={(e) => setNewComment(e.target.value)}
          placeholder="Write a comment..."
        />
        <button
          onClick={handleAddComment}
          className="bg-emerald-500 text-emerald-50 px-3 py-1 rounded mt-1"
        >
          Add Comment
        </button>
      </div>
    </div>
  );
};

export default CommentList;
