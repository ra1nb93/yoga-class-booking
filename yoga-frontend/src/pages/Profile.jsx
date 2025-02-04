import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

function Profile() {
  const [user, setUser] = useState({});
  const [bookings, setBookings] = useState([]);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const navigate = useNavigate();

  // Funzione per ottenere l'ID utente dal token JWT
  const getUserIdFromToken = () => {
    const token = localStorage.getItem('token');
    if (!token) {
      console.error('Token is missing or user not authenticated');
      return null;
    }

    try {
      const decoded = JSON.parse(atob(token.split('.')[1])); // Decodifica il payload del token
      return decoded.user_id; // Restituisce l'ID utente
    } catch (err) {
      console.error('Failed to decode token:', err);
      return null;
    }
  };

  // Recupera i dati utente dal backend
  const fetchUserData = async () => {
    const userId = getUserIdFromToken();
    if (!userId) {
      setError('Session expired. Please log in again.');
      navigate('/'); // Reindirizza al login
      return;
    }

    try {
      const response = await axios.get(`http://localhost:8000/api/user/get_user.php?user_id=${userId}`);
      setUser(response.data);
    } catch (err) {
      console.error('Failed to fetch user data:', err);
      setError('Failed to fetch user data.');
    }
  };

  // Recupera le lezioni prenotate
  const fetchBookings = async () => {
    const userId = getUserIdFromToken();
    if (!userId) {
      setError('Session expired. Please log in again.');
      navigate('/'); // Reindirizza al login
      return;
    }

    try {
      const response = await axios.get(`http://localhost:8000/api/user/get_user_bookings.php?user_id=${userId}`);
      setBookings(response.data);
    } catch (err) {
      console.error('Failed to fetch bookings:', err);
      setError('Failed to fetch bookings.');
    }
  };

  // Aggiorna i dati del profilo
  const handleUpdateProfile = async () => {
    const userId = getUserIdFromToken();
    if (!userId) {
      setError('Session expired. Please log in again.');
      navigate('/'); // Reindirizza al login
      return;
    }

    try {
      const response = await axios.post('http://localhost:8000/api/user/update_user.php', {
        user_id: userId,
        name: user.name,
        email: user.email,
      });

      if (response.data.success) {
        setSuccess(response.data.success);
      } else {
        setError(response.data.error);
      }
    } catch (err) {
      console.error('Failed to update profile:', err);
      setError('Failed to update profile.');
    }
  };

  useEffect(() => {
    fetchUserData();
    fetchBookings();
  }, []);

  return (
    <div className="p-6 bg-gray-100 min-h-screen">
      <h1 className="text-2xl font-bold mb-4">Profile</h1>
      {error && <p className="text-red-500 mb-4">{error}</p>}
      {success && <p className="text-green-500 mb-4">{success}</p>}
      <div className="bg-white p-4 rounded shadow mb-6">
        <h2 className="text-xl font-bold mb-4">User Details</h2>
        <div className="mb-4">
          <label className="block font-semibold mb-2">Name</label>
          <input
            type="text"
            value={user.name || ''}
            onChange={(e) => setUser({ ...user, name: e.target.value })}
            className="w-full p-2 border rounded"
          />
        </div>
        <div className="mb-4">
          <label className="block font-semibold mb-2">Email</label>
          <input
            type="email"
            value={user.email || ''}
            onChange={(e) => setUser({ ...user, email: e.target.value })}
            className="w-full p-2 border rounded"
          />
        </div>
        <button
          onClick={handleUpdateProfile}
          className="bg-emerald-500 text-white py-2 px-4 rounded hover:bg-blue-600"
        >
          Update Profile
        </button>
      </div>
      <div className="bg-white p-4 rounded shadow">
        <h2 className="text-xl font-bold mb-4">Booked Classes</h2>
        <ul>
          {bookings.length > 0 ? (
            bookings.map((booking) => (
              <li key={booking.id}>
                {booking.title} - {new Date(booking.date).toLocaleString()}
              </li>
            ))
          ) : (
            <p>No bookings available.</p>
          )}
        </ul>
      </div>
    </div>
  );
}

export default Profile;
