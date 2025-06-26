import React from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';

export default function Dashboard() {
  return (
    <div>
      <h1>Dashboard</h1>
      <InertiaLink href="/">Zurück zur Startseite</InertiaLink>
    </div>
  );
}