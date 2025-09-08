import { Router } from 'express';
const router = Router();
router.get('/', async (_req, res) => res.json([]));
router.get('/:id', async (req, res) => res.json({ id: String(req.params.id), name: 'عميل', phone: '9665xxxxxxx', source: 'listing', notes: '' }));
export default router;
