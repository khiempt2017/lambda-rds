/**
 * categories-mysql.model.ts
 * BP-api-serverless
 * MySQL version of Categories model
 * Created on 2025/10/18
 * Copyright (c) 2025年 OMRON HEALTHCARE Co.,Ltd. All rights reserved.
 */

import { MySQLModel } from '../db/mysql-connection';
import { ResultSetHeader, RowDataPacket } from 'mysql2/promise';

// Base interface without RowDataPacket
export interface CategoriesData {
  id?: number;
  name: string;
  description: string | null;
  status: number;
  created_at: string | null;
  updated_at: string | null;
}

// Extended interface with RowDataPacket for MySQL query results
export interface Categories extends CategoriesData, RowDataPacket {}

export const defaultCategories: CategoriesData = {
  id: undefined,
  name: '',
  description: null,
  status: 1,
  created_at: null,
  updated_at: null,
};

/**
 * Categories Model for MySQL
 * Example usage:
 * 
 * // Create instance
 * const categoriesModel = new CategoriesMySQLModel();
 * 
 * // Find by name
 * const category = await categoriesModel.findByName('Apple');
 * 
 * // Create new category
 * const result = await categoriesModel.createCategory({
 *   name: 'Dell',
 *   description: 'Dell laptops and computers',
 * });
 * 
 * // Update category
 * await categoriesModel.updateCategory(1, {
 *   description: 'Updated description'
 * });
 * 
 * // Delete category
 * await categoriesModel.deleteCategory(1);
 */
export class CategoriesMySQLModel extends MySQLModel<Categories> {
  constructor() {
    const tableName = 'categories';
    super(tableName);
  }

  /**
   * Find category by name
   * @param name Category name
   * @returns Category record or undefined
   */
  async findByName(name: string): Promise<Categories | undefined> {
    return await this.findOneBy({ name });
  }

  /**
   * Find categories by status
   * @param status Status (1 = active, 0 = inactive)
   * @returns Array of category records
   */
  async findByStatus(status: number): Promise<Categories[]> {
    return await this.findBy({ status });
  }

  /**
   * Find active categories
   * @returns Array of active category records
   */
  async findActiveCategories(): Promise<Categories[]> {
    return await this.findByStatus(1);
  }

  /**
   * Create a new category record
   * @param data Category data
   * @returns Insert result
   */
  async createCategory(data: Omit<CategoriesData, 'id'>): Promise<ResultSetHeader> {
    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const categoryData = {
      ...data,
      created_at: data.created_at || now,
      updated_at: data.updated_at || now,
    };
    return await this.insert(categoryData as Partial<Categories>);
  }

  /**
   * Update category record by id
   * @param id Category ID
   * @param data Data to update
   * @returns Update result
   */
  async updateCategory(
    id: number,
    data: Partial<Omit<CategoriesData, 'id'>>
  ): Promise<ResultSetHeader> {
    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');
    const updateData = {
      ...data,
      updated_at: now,
    };
    return await this.update(
      updateData as Partial<Categories>,
      { id }
    );
  }

  /**
   * Delete category record by id
   * @param id Category ID
   * @returns Delete result
   */
  async deleteCategory(id: number): Promise<ResultSetHeader> {
    return await this.delete({ id });
  }

  /**
   * Get categories with product count
   * @returns Array of categories with product count
   */
  async findWithProductCount(): Promise<any[]> {
    const sql = `
      SELECT 
        c.*,
        COUNT(p.id) as product_count
      FROM ${this.tableName} c
      LEFT JOIN products p ON c.id = p.category_id AND p.status = 1
      GROUP BY c.id
      ORDER BY c.name ASC
    `;
    return await this.query<any>(sql);
  }

  /**
   * Check if category can be deleted (no products associated)
   * @param id Category ID
   * @returns True if can be deleted, false otherwise
   */
  async canDeleteCategory(id: number): Promise<boolean> {
    const sql = `
      SELECT COUNT(*) as product_count
      FROM products
      WHERE category_id = ?
    `;
    const result = await this.queryOne<any>(sql, [id]);
    return result?.product_count === 0;
  }

  /**
   * Get category statistics
   * @returns Statistics object
   */
  async getStatistics(): Promise<any> {
    const sql = `
      SELECT 
        COUNT(*) as total_categories,
        COUNT(CASE WHEN status = 1 THEN 1 END) as active_categories,
        COUNT(CASE WHEN status = 0 THEN 1 END) as inactive_categories
      FROM ${this.tableName}
    `;
    return await this.queryOne<any>(sql);
  }
}
