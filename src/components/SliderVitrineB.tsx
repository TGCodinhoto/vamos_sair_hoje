'use client';

import React, { useState, useEffect, useRef } from 'react';
import Image from 'next/image';

const SliderVitrineB = () => {
  const [currentIndex, setCurrentIndex] = useState(0);
  const sliderRef = useRef<HTMLDivElement>(null);
  const slides = [
    { src: "/image01.webp", alt: "Imagem 1" },
    { src: "/image02.webp", alt: "Imagem 2" },
    { src: "/image03.webp", alt: "Imagem 3" },
  ];

  const goToSlide = (index: number) => {
    let newIndex = index;
    if (index < 0) {
      newIndex = slides.length - 1;
    } else if (index >= slides.length) {
      newIndex = 0;
    }
    setCurrentIndex(newIndex);
    if (sliderRef.current) {
      sliderRef.current.style.transform = `translateX(-${newIndex * 100}%)`;
    }
  };

  useEffect(() => {
    const interval = setInterval(() => {
      goToSlide(currentIndex + 1);
    }, 3000);

    return () => clearInterval(interval);
  }, [currentIndex]);

  return (
    <div className="slider-container">
      <div className="slider" ref={sliderRef}>
        {slides.map((slide, index) => (
          <div className="slide" key={index}>
            <Image src={slide.src} alt={slide.alt} width={300} height={200} />
          </div>
        ))}
      </div>
      <button className="prev-button" onClick={() => goToSlide(currentIndex - 1)}>Anterior</button>
      <button className="next-button" onClick={() => goToSlide(currentIndex + 1)}>Próximo</button>
    </div>
  );
};

export default SliderVitrineB;