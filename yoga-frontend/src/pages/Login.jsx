import React, { useState } from 'react';
import axios from 'axios';
import { Link, useNavigate } from 'react-router-dom';


function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    setError(''); // reset any previous errors

    try {
      const response = await axios.post(
        'https://yoga-class-booking-production.up.railway.app/api/user/login.php',
        { email, password },
        { withCredentials: true } // needed to include cookies/credentials
      );

      console.log('Backend response:', response.data);

      // Check if the token is present in the response
      if (response.data.token) {
        localStorage.setItem('token', response.data.token); // Save the token

        // ✅ Now `user` is included in the response, so we save it in localStorage
        if (response.data.user) {
          localStorage.setItem(
            'user',
            JSON.stringify({
              id: response.data.user.id,
              name: response.data.user.name,
            })
          );
          console.log('User saved in localStorage:', localStorage.getItem('user'));
        } else {
          console.error('Error: `user` not found in the backend response.');
        }

        navigate('/dashboard'); // Redirect to the dashboard
      } else {
        setError(response.data.error || 'Login failed.');
      }
    } catch (err) {
      console.error('Login error:', err);
      setError(err.response?.data?.error || 'Invalid email or password.');
    }
  };

  return (
    <div className=" rounded-full h-screen bg-cover bg-center bg-[linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)),url('../assets/stones.jpg')]">

      <div className="relative flex items-center justify-center h-full">
        <form onSubmit={handleLogin} className="p-6 bg-white rounded shadow-md w-80 z-10">
          <h1 className="text-2xl font-bold mb-4">Login</h1>
          {error && <p className="text-red-500 mb-4">{error}</p>}
          <input
            type="email"
            placeholder="Email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            className="w-full p-2 mb-4 border rounded"
            required
          />
          <input
            type="password"
            placeholder="Password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            className="w-full p-2 mb-4 border rounded"
            required
          />
          <button
             className="bg-emerald-500 text-white py-1 px-4 rounded hover:bg-emerald-600"
            type="submit"
          >
            Login
          </button>
          <p className="mt-4 text-sm text-center">
            Don’t have an account?{' '}
                    <Link to="/register" className="text-emerald-500 hover:underline">
                        Register
                    </Link>
          </p>
        </form>
      </div>
    </div>
  );
}

export default Login;
