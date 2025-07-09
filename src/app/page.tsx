import Header from '@/components/Header';
import Footer from '@/components/Footer';
import SliderMan from '@/components/SliderMan';
import SliderPrime from '@/components/SliderPrime';
import EventCard from '@/components/EventCard';

export default function Home() {
  return (
    <div>
      <Header />
      <main>
        <section className="events-container">
          <div className="carousel-container">
            <SliderMan />
          </div>
          
          <div className="event-row">
            <EventCard
              imageUrl="/image01.webp"
              altText="Show de Rock"
              title="Show de Rock com Banda XYZ"
              location="Arena de Eventos"
              date="20/07/2025"
              type="Show"
            />
            <EventCard
              imageUrl="/image02.webp"
              altText="Baile de Gala"
              title="Baile de Gala Beneficente"
              location="Salão Nobre"
              date="25/07/2025"
              type="Baile"
            />
          </div>

          <div className="carousel-container">
            <SliderPrime />
          </div>

          <div className="event-row">
            <EventCard
              imageUrl="/image03.webp"
              altText="Festa Temática"
              title="Festa Anos 80"
              location="Clube Noturno"
              date="01/08/2025"
              type="Festa"
            />
            <EventCard
              imageUrl="/image04.webp"
              altText="Stand-up Comedy"
              title="Noite de Stand-up Comedy"
              location="Teatro Municipal"
              date="05/08/2025"
              type="Teatro"
            />
          </div>
        </section>
      </main>
      <Footer />
    </div>
  );
}
