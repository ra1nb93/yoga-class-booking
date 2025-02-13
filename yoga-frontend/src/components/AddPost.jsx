import React, { useState } from 'react';
import axios from 'axios';

const AddPost = ({ onPostAdded = () => {} }) => {
  const [content, setContent] = useState('');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!content.trim()) {
      setError('Content is required');
      return;
    }

    try {
      const token = localStorage.getItem('token');
      if (!token) {
        setError('Missing token. Please log in again.');
        return;
      }

      const response = await axios.post(
        'https://yoga-class-booking-production.up.railway.app/api/posts/add_post.php',
        { content: content.trim() }, // Send only text, no user_id
        {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
          },
        }
      );

      console.log('Backend response:', response.data);

      if (response.data.success) {
        setSuccess('Post added successfully!');
        setContent('');
        onPostAdded(); // Refresh the post list
      } else {
        setError(response.data.error || 'Unknown error');
      }
    } catch (err) {
      console.error('Error sending the post:', err);
      setError('Network or server-side error.');
    }
  };

  return (
    <form onSubmit={handleSubmit} className="mb-6">
      {error && <p className="text-red-500">{error}</p>}
      {success && <p className="text-green-500">{success}</p>}
      <textarea
        value={content}
        onChange={(e) => setContent(e.target.value)}
        placeholder="Write something..."
        className="w-full p-2 border rounded"
        rows="4"
      />
      <button type="submit" className="bg-emerald-200 text-emerald-600 py-2 px-4 rounded">
        Publish
      </button>
    </form>
  );
};

export default AddPost;
