import React from 'react';
import { Link, useNavigate } from 'react-router-dom';


const Footer = () => {
    const navigate = useNavigate();


    return (
        <nav className="bg-emerald-800 text-emerald-100 p-5 rounded-full mt-3 text-md  font-bold">
            <div className="container mx-auto flex justify-items-center items-center">

               
                    Alessio Martis &#169;
                    
                
            </div>
        </nav>
    );
};

export default Footer;
