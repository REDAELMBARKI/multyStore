import React, { useState } from "react";
import { Menu, X } from "lucide-react";

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);

  const toggleMenu = () => {
    setIsOpen(!isOpen);
  };

  return (
    <nav className="bg-saas-black bg-opacity-90 backdrop-blur-sm sticky top-0 z-50 border-b border-saas-darkGray">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-16 items-center">
          <div className="flex-shrink-0 flex items-center">
            <a href="#" className="flex items-center">
              <span className="text-2xl font-bold bg-gradient-to-r from-saas-orange to-amber-500 bg-clip-text text-transparent">
                Unistore
              </span>
            </a>
          </div>

          <div className="hidden md:block">
            <div className="flex items-center space-x-4">
              <a href="#home" className="px-3 py-2 text-sm font-medium text-white hover:text-saas-orange transition-colors">
                Home
              </a>
              <a href="#roadmap" className="px-3 py-2 text-sm font-medium text-white hover:text-saas-orange transition-colors">
                Roadmap
              </a>
              <a href="#pricing" className="px-3 py-2 text-sm font-medium text-white hover:text-saas-orange transition-colors">
                Pricing
              </a>
              <a href="#contact" className="px-3 py-2 text-sm font-medium text-white hover:text-saas-orange transition-colors">
                Contact
              </a>
            </div>
          </div>

                <div className="hidden md:block visible">
          <a
            href="http://192.168.1.4:8000"
            target="_blank"
            rel="noopener noreferrer"
            className="btn-primary w-full"
          >
            Support WhatsApp
          </a>
        </div>

          <div className="md:hidden flex items-center">
            <button
              onClick={toggleMenu}
              className="inline-flex items-center justify-center p-2 rounded-md text-white focus:outline-none"
            >
              {isOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
            </button>
          </div>
        </div>
      </div>

      {isOpen && (
        <div className="md:hidden bg-saas-darkGray">
          <div className="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="#home" className="block px-3 py-2 text-base font-medium text-white hover:text-saas-orange transition-colors" onClick={() => setIsOpen(false)}>
              Home
            </a>
            <a href="#roadmap" className="block px-3 py-2 text-base font-medium text-white hover:text-saas-orange transition-colors" onClick={() => setIsOpen(false)}>
              Roadmap
            </a>
            <a href="#pricing" className="block px-3 py-2 text-base font-medium text-white hover:text-saas-orange transition-colors" onClick={() => setIsOpen(false)}>
              Pricing
            </a>
            <a href="#contact" className="block px-3 py-2 text-base font-medium text-white hover:text-saas-orange transition-colors" onClick={() => setIsOpen(false)}>
              Contact
            </a>
          </div>
        </div>
      )}
    </nav>
  );
};

export default Navbar;
