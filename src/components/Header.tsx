import React from 'react';

const Header = () => {
  return (
    <header>
      <section className="search-bar">
        <h2>Encontre Seu Evento</h2>
        
        <nav className="quick-menu">
          <div className="container">
            <ul>
              <li><a href="#" aria-label="Shows"><i className="fas fa-music"></i> Shows</a></li>
              <li><a href="#" aria-label="Bailes"><i className="fas fa-compact-disc"></i> Bailes</a></li>
              <li><a href="#" aria-label="Teatro"><i className="fas fa-theater-masks"></i> Teatro</a></li>
              <li><a href="#" aria-label="Esporte"><i className="fas fa-futbol"></i> Esporte</a></li>
              <li><a href="#" aria-label="Exposições"><i className="fas fa-paint-brush"></i> Exposições</a></li>
              <li><a href="#" aria-label="Festivais"><i className="fas fa-fire"></i> Festivais</a></li>
            </ul>
          </div>
        </nav>
        
        <form>
          <div className="form-group">
            <label htmlFor="city">Cidade:</label>
            <select id="eventType" name="eventType">
              <option value="">Todos</option>
              <option value="1">São Paulo</option>
              <option value="2">Ribeirão Preto</option>
              <option value="3">São Carlos</option>
            </select>
          </div>
          <div className="form-group">
            <label htmlFor="startDate">Data Inicial:</label>
            <input type="date" id="startDate" name="startDate" />
          </div>
          <div className="form-group">
            <label htmlFor="endDate">Data Final:</label>
            <input type="date" id="endDate" name="endDate" />
          </div>
          <div className="form-group">
            <label htmlFor="eventType">Tipo de Evento:</label>
            <select id="eventType" name="eventType">
              <option value="">Todos</option>
              <option value="1">Show</option>
              <option value="2">Baile</option>
              <option value="3">Festa</option>
              <option value="4">Teatro</option>
              <option value="5">Esporte</option>
            </select>
          </div>
          <div>
            <button type="submit">Pesquisar</button>
          </div>
        </form>
      </section>
    </header>
  );
};

export default Header;