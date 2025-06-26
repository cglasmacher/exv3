import axios from 'axios';
import React, { useState } from 'react';
import { Inertia } from '@inertiajs/inertia';

export default function Home() {
  const [form, setForm] = useState({ sender_postcode: '', sender_country: 'DE', sender_city: '' });

  async function fetchCity() {
    const { sender_country, sender_postcode } = form;
    if (sender_postcode.length < 3) return;
    try {
      const res = await axios.get('/api/locations/cities', {
        params: { country: sender_country, postalcode: sender_postcode }
      });
      if (res.data.length) setForm(f => ({ ...f, sender_city: res.data[0] }));
    } catch (e) { console.error(e); }
  }

  function handleChange(e: React.ChangeEvent<HTMLInputElement> | React.ChangeEvent<HTMLSelectElement>) {
    const { id, value } = e.target;
    setForm(f => ({ ...f, [id]: value }));
  }

  function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    Inertia.post('/api/quote-requests', form);
  }

  return (
    <form onSubmit={handleSubmit}>
      <div>
        <label htmlFor="sender_country">Land</label>
        <select id="sender_country" value={form.sender_country} onChange={handleChange}>
          {/* mappe Country-Optionen hier */}
          <option value="DE">Germany</option>
          <option value="AT">Austria</option>
        </select>
      </div>
      <div>
        <label htmlFor="sender_postcode">PLZ</label>
        <input id="sender_postcode" value={form.sender_postcode} onChange={handleChange} onBlur={fetchCity} />
      </div>
      <div>
        <label htmlFor="sender_city">Stadt</label>
        <input id="sender_city" value={form.sender_city} readOnly />
      </div>
      <button type="submit">Quote anfordern</button>
    </form>
  );
}