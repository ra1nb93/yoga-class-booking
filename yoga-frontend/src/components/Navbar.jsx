import React from 'react';
import { Link, useNavigate } from 'react-router-dom';


const Navbar = () => {
    const navigate = useNavigate();

    
    const handleLogout = () => {
        localStorage.removeItem('token'); // Rimuovi il token JWT
        navigate('/'); // Reindirizza alla pagina di login
    };

    const isAuthenticated = !!localStorage.getItem('token');
    {isAuthenticated && <Navbar />}

    return (
        <nav className="bg-emerald-800 text-emerald-100 p-5 rounded-full mt-3">
            <div className="container mx-auto flex justify-between items-center">
                <h1 className="text-2xl font-bold text-emerald-100">
                    <Link to="/" className="hover:underline">
                        Yoga Class
                    </Link>
                </h1>
                <div className="text-md space-x-4 font-bold">
                    <Link to="/board" className="hover:underline">
                        Home
                    </Link>
                    <Link to="/dashboard" className="hover:underline">
                        Classes
                    </Link>
                    <Link to="/profile" className="hover:underline">
                        Profile
                    </Link>
                    <button
                        onClick={handleLogout}
                        className="bg-emerald-900 py-1 px-3 rounded hover:bg-red-600"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </nav>
    );
};

export default Navbar;
