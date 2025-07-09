'use client';

import React, { useState, useEffect } from 'react';
import Image from 'next/image';

const SliderMan = () => {
  const [currentSlide, setCurrentSlide] = useState(0);
  const slides = [
    { src: "/image01.webp", alt: "Anúncio 1" },
    { src: "/image02.webp", alt: "Anúncio 2" },
    { src: "/image03.webp", alt: "Anúncio 3" },
  ];
  const slideIntervals = [3000, 5000, 4000];

  useEffect(() => {
    const nextSlide = () => {
      setCurrentSlide((prevSlide) => (prevSlide + 1) % slides.length);
    };

    const timer = setTimeout(nextSlide, slideIntervals[currentSlide]);

    return () => clearTimeout(timer);
  }, [currentSlide, slides.length]);

  return (
    <div className="carousel-container">
      {slides.map((slide, index) => (
        <div
          key={index}
          className="carousel-slide"
          style={{ display: index === currentSlide ? 'block' : 'none' }}
        >
          <Image src={slide.src} alt={slide.alt} width={800} height={400} />
        </div>
      ))}
    </div>
  );
};

export default SliderMan;