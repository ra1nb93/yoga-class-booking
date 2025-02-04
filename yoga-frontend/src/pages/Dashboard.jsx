import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

function Dashboard() {
  const [classes, setClasses] = useState([]);
  const [notifications, setNotifications] = useState([]);
  const [unreadCount, setUnreadCount] = useState(0);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [showNotifications, setShowNotifications] = useState(false);
  const navigate = useNavigate();

  const getUserIdFromToken = () => {
    const token = localStorage.getItem('token');
    if (!token) {
      console.error('User is not authenticated');
      return null;
    }
    try {
      const decoded = JSON.parse(atob(token.split('.')[1])); // Decode JWT payload
      return decoded.user_id;
    } catch (err) {
      console.error('Failed to decode token:', err);
      return null;
    }
  };

  const fetchClasses = async () => {
    const token = localStorage.getItem('token');
    try {
      const response = await axios.get(
        'http://localhost:8000/api/class/get_classes.php',
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );
      setClasses(Array.isArray(response.data) ? response.data : []);
    } catch (err) {
      setError('Failed to load classes. Please try again.');
    }
  };

  const fetchNotifications = async () => {
    const token = localStorage.getItem('token');
    try {
      const response = await axios.get(
        'http://localhost:8000/api/notification/get_notification.php',
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );

      if (Array.isArray(response.data)) {
        setNotifications(response.data);
        setUnreadCount(response.data.filter((n) => !n.read).length);
      } else {
        setNotifications([]);
        setUnreadCount(0);
      }
    } catch (err) {
      console.error('Failed to fetch notifications:', err);
      setNotifications([]);
      setUnreadCount(0);
    }
  };

  useEffect(() => {
    const token = localStorage.getItem('token');
    if (!token) {
      navigate('/');
    }
    fetchClasses();
    fetchNotifications();
  }, [navigate]);

  const handleMarkAsRead = () => {
    setUnreadCount(0);
    setShowNotifications(false);
  };

  const handleBookClass = async (classId) => {
    setError('');
    setSuccess('');
    const token = localStorage.getItem('token');
    const userId = getUserIdFromToken();

    if (!token || !userId) {
      setError('User is not authenticated.');
      return;
    }

    try {
      const response = await axios.post(
        'http://localhost:8000/api/class/book_class.php',
        { user_id: userId, class_id: classId },
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );

      if (response.status === 200 && response.data.success) {
        setSuccess(response.data.success);
        await fetchNotifications(); // Update notifications
      } else {
        setError('Unexpected response from server.');
      }
    } catch (err) {
      if (err.response && err.response.status === 400) {
        setError(err.response.data.error || 'You have already booked this class.');
      } else {
        setError('Failed to book the class. Please try again.');
      }
    }
  };

  const handleCancelBooking = async (classId) => {
    setError('');
    setSuccess('');
    const token = localStorage.getItem('token');
    const userId = getUserIdFromToken();

    if (!token || !userId) {
      setError('User is not authenticated.');
      return;
    }

    try {
      const response = await axios.post(
        'http://localhost:8000/api/class/cancel_booking.php',
        { user_id: userId, class_id: classId },
        {
          headers: { Authorization: `Bearer ${token}` },
        }
      );

      if (response.status === 200 && response.data.success) {
        setSuccess(response.data.success);
        await fetchNotifications(); // Update notifications
      } else if (response.data.error) {
        setError(response.data.error);
      } else {
        setError('Unexpected response from server.');
      }
    } catch (err) {
      console.error('Error during cancellation:', err);
      if (err.response && err.response.data && err.response.data.error) {
        setError(err.response.data.error);
      } else if (err.message) {
        setError('Network error: Failed to communicate with the server.');
      } else {
        setError('An unexpected error occurred. Please try again.');
      }
    }
  };

  const handleLogout = () => {
    localStorage.removeItem('token');
    navigate('/');
  };

  return (
    <div className="p-6 bg-gray-100 min-h-screen">
      <div className="flex justify-between items-center mb-4">
        <h1 className="text-2xl font-bold">Available Classes</h1>
      </div>

      {/* Error and success messages */}
      {error && <p className="text-red-500 mb-4">{error}</p>}
      {success && <p className="text-emerald-600 mb-4">{success}</p>}

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        
        {classes.length > 0 ? (
          classes.map((classItem) => (
            <div key={classItem.id} className="p-4 bg-white rounded shadow">
              <h2 className="text-xl font-semibold">{classItem.title}</h2>
              <p>Date: {new Date(classItem.date).toLocaleString()}</p>
              <p>Max Participants: {classItem.max_participants}</p>
              <div className="flex gap-2 mt-2">
                <button
                  className="bg-emerald-500 text-white py-1 px-4 rounded hover:bg-emerald-600"
                  onClick={() => handleBookClass(classItem.id)}
                >
                  Book
                </button>
                <button
                  className="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600"
                  onClick={() => handleCancelBooking(classItem.id)}
                >
                  Cancel
                </button>
              </div>
            </div>
          ))
        ) : (
          <p>No classes available</p>
        )}
      </div>
    </div>
  );
}

export default Dashboard;
