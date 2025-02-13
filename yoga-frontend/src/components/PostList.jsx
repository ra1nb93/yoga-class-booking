import React, { useEffect, useState } from 'react';
import axios from 'axios';
import CommentList from './CommentList'; // Make sure the path is correct

const PostList = ({ refreshTrigger }) => {
  const [posts, setPosts] = useState([]);
  const [editingPostId, setEditingPostId] = useState(null);
  const [editContent, setEditContent] = useState('');

  const currentUser = localStorage.getItem('user')
    ? JSON.parse(localStorage.getItem('user'))
    : null;

  const fetchPosts = () => {
    axios
      .get('https://yoga-class-booking-production.up.railway.app/api/posts/get_posts.php')
      .then((response) => {
        console.log('Data received from the server:', response.data);
        setPosts(response.data);
      })
      .catch((error) => console.error('Failed to fetch posts:', error));
  };

  useEffect(() => {
    fetchPosts();
  }, [refreshTrigger]);

  // For debugging
  console.log('Logged-in user:', currentUser);

  // Start editing a post
  const startEditing = (postId, currentContent) => {
    setEditingPostId(postId);
    setEditContent(currentContent);
  };

  // Save the edited post
  const saveEdit = async (postId) => {
    try {
      const token = localStorage.getItem('token');
      await axios.put(
        'https://yoga-class-booking-production.up.railway.app/api/posts/update_post.php',
        { post_id: postId, content: editContent },
        {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
          },
        }
      );
      alert('Post updated successfully');
      setEditingPostId(null);
      setEditContent('');
      fetchPosts(); // reload the list
    } catch (error) {
      console.error('Error while updating the post:', error);
      alert('Error while updating the post.');
    }
  };

  // Cancel editing
  const cancelEdit = () => {
    setEditingPostId(null);
    setEditContent('');
  };

  // Delete a post
  const handleDelete = async (postId) => {
    try {
      const token = localStorage.getItem('token');
      await axios.delete('https://yoga-class-booking-production.up.railway.app/api/posts/delete_post.php', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
        data: { post_id: postId },
      });
      alert('Post deleted successfully');
      fetchPosts();
    } catch (error) {
      console.error('Error while deleting the post:', error);
      alert('Error while deleting the post.');
    }
  };



const handleTogglePostLike = async (postId) => {
    if (!currentUser) {
      alert('Please log in to like/unlike this post');
      return;
    }
    try {
      const token = localStorage.getItem('token');
      await axios.post(
        'https://yoga-class-booking-production.up.railway.app/api/posts/toggle_post_like.php', // changed endpoint
        { post_id: postId },
        {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        }
      );
      // Refresh to update the new like_count
      fetchPosts();
    } catch (error) {
      console.error('Error toggling post like:', error);
    }
  };
  

  return (
    <div>
      <h2 className="text-2xl font-bold mb-4">Board</h2>

      {posts.map((post) => (
        <div key={post.id} className="bg-emerald-50 p-4 mb-4 rounded shadow">
          {editingPostId === post.id ? (
            <textarea
              className="w-full p-2 border "
              value={editContent}
              onChange={(e) => setEditContent(e.target.value)}
            />
          ) : (
            <p className="text-gray-700">{post.content}</p>
          )}

          <small className="text-gray-500">
            Posted by {post.author} on {new Date(post.created_at).toLocaleString()}
          </small>

          {/* Edit/Delete if user is the owner */}
          {currentUser && Number(post.user_id) === Number(currentUser.id) && (
            <div className="mt-2">
              {editingPostId === post.id ? (
                <>
                  <button
                    className="text-green-500 hover:underline mr-2"
                    onClick={() => saveEdit(post.id)}
                  >
                    Save
                  </button>
                  <button
                    className="text-gray-500 hover:underline"
                    onClick={cancelEdit}
                  >
                    Cancel
                  </button>
                </>
              ) : (
                <>
                  <button
                    className="text-emerald-500 hover:underline mr-2"
                    onClick={() => startEditing(post.id, post.content)}
                  >
                    Edit
                  </button>
                  <button
                    className="text-red-500 hover:underline"
                    onClick={() => handleDelete(post.id)}
                  >
                    Delete
                  </button>
                </>
              )}
            </div>
          )}

          {/* Display like count and single "Like" button */}
          <div className="mt-2">
            
            {currentUser && (
              <button
                className="text-emerald-500 hover:underline"
                onClick={() => handleTogglePostLike(post.id)}
              >
                Like {post.like_count}
              </button>
            )}
          </div>

          {/* CommentList */}
          <CommentList postId={post.id} />
        </div>
      ))}
    </div>
  );
};

export default PostList;
