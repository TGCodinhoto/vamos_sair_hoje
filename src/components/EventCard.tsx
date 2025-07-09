import React from 'react';
import Image from 'next/image';

interface EventCardProps {
  imageUrl: string;
  altText: string;
  title: string;
  location: string;
  date: string;
  type: string;
}

const EventCard: React.FC<EventCardProps> = ({ imageUrl, altText, title, location, date, type }) => {
  return (
    <div className="event-card">
      <Image src={imageUrl} alt={altText} width={300} height={200} />
      <h3>{title}</h3>
      <p>Local: {location}</p>
      <p>Data: {date}</p>
      <p>Tipo: {type}</p>
      <a href="#" className="details-button">Ver Detalhes</a>
    </div>
  );
};

export default EventCard;